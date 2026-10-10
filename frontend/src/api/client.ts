import { useAuth } from '@/composable/useAuth'
import { ApiError, type ApiErrorBody, ApiErrorCode } from '@/api/errors'
import { useAuthStore } from '@/store/useAuthStore'

export class ApiClient<T> {
  method = ''
  path = ''
  headers: Record<string, string> = {
    'Content-Type': 'application/json',
  }
  private refreshPromise: Promise<boolean> | null = null

  static get<T>(
    path: string,
    body: Record<string, any> | null = null,
  ): Promise<ApiResponse<T>> {
    return this.request<T>('GET', path, body)
  }

  static delete<T>(
    path: string,
    body: Record<string, any> | null = null,
  ): Promise<ApiResponse<T>> {
    return this.request<T>('DELETE', path, body)
  }

  static post<T>(
    path: string,
    body: Record<string, any> | null = null,
  ): Promise<ApiResponse<T>> {
    return this.request<T>('POST', path, body)
  }

  static put<T>(
    path: string,
    body: Record<string, any> | null = null,
  ): Promise<ApiResponse<T>> {
    return this.request<T>('PUT', path, body)
  }

  static async request<T>(
    method: string,
    path: string,
    body: Record<string, any> | null = null,
  ): Promise<ApiResponse<T>> {
    const self = new this<T>()

    self.method = method
    self.path = path.replace(/^\/+|\/+$/g, '')

    return await self.send(body)
  }

  header(name: string, value: string): this {
    this.headers[name] = value

    return this
  }

  private async send(
    body: Record<string, any> | null = null,
  ): Promise<ApiResponse<T>> {
    const jwt = localStorage.getItem('jwt')

    if (jwt != null) {
      this.header('Authorization', `Bearer ${jwt}`)
    }

    const port = location.port !== '' ? ':' + location.port : ''
    let url = `http://${location.hostname}${port}/api/${this.path}`

    if (this.method === 'GET' && body != null) {
      const query = this.toQueryString(body)
      if (query) url += `?${query}`
    }

    const res = await fetch(url, {
      method: this.method,
      headers: this.headers,
      body: body != null && this.method !== 'GET' ? JSON.stringify(body) : null,
    })

    if (res.status === 401 && this.path !== 'auth/refresh') {
      this.refreshPromise ??= useAuth()
        .refreshSession()
        .finally(() => {
          this.refreshPromise = null
        })
      await this.refreshPromise
      return await this.send(body)
    }

    if (res.status === 403) {
      const body = await res
        .clone()
        .json()
        .catch(() => null)

      if (body?.code === ApiErrorCode.ProfileIncomplete) {
        useAuthStore().profileIncomplete = true
      }
    }

    return new ApiResponse<T>(res)
  }

  private toQueryString(params: Record<string, any>): string {
    const search = new URLSearchParams()

    for (const [key, value] of Object.entries(params)) {
      if (value === null || value === undefined || value === '') continue

      if (Array.isArray(value)) {
        if (value.length === 0) continue
        search.append(key, value.join(','))
      } else {
        search.append(key, String(value))
      }
    }

    return search.toString()
  }
}

class ApiResponse<T> {
  constructor(private response: Response) {}

  async json(): Promise<T> {
    if (!this.response.ok) {
      const body = (await this.response
        .json()
        .catch(() => ({ message: this.response.statusText }))) as ApiErrorBody

      throw new ApiError(this.response.status, body.code ?? null, body.message)
    }

    return (await this.response.json()) as T
  }

  status(): number {
    return this.response.status
  }

  isForbiddenStatus(): boolean {
    return this.response.status === 403
  }

  ok(): boolean {
    return this.response.ok
  }
}
