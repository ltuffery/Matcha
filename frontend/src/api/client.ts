import { useAuth } from '@/composable/useAuth'
import { ApiError, type ApiErrorBody, ApiErrorCode } from '@/api/errors'
import { useAuthStore } from '@/store/useAuthStore'

type Body = Record<string, any> | FormData | null

export class ApiClient<T> {
  method = ''
  path = ''
  headers: Record<string, string> = {
    'Content-Type': 'application/json',
  }
  private retried = false
  private static refreshPromise: Promise<boolean> | null = null

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

  post(path: string, body: Body = null): Promise<ApiResponse<T>> {
    return this.request('POST', path, body)
  }

  static post<T>(path: string, body: Body = null): Promise<ApiResponse<T>> {
    return this.request<T>('POST', path, body)
  }

  static put<T>(
    path: string,
    body: Record<string, any> | null = null,
  ): Promise<ApiResponse<T>> {
    return this.request<T>('PUT', path, body)
  }

  async request(
    method: string,
    path: string,
    body: Body = null,
  ): Promise<ApiResponse<T>> {
    this.method = method
    this.path = path.replace(/^\/+|\/+$/g, '')

    return await this.send(body)
  }

  static async request<T>(
    method: string,
    path: string,
    body: Record<string, any> | null = null,
  ): Promise<ApiResponse<T>> {
    const self = new this<T>()

    return self.request(method, path, body)
  }

  header(name: string, value: string): this {
    this.headers[name] = value

    return this
  }

  static header<T>(name: string, value: string): ApiClient<T> {
    const self = new this<T>()
    self.headers[name] = value

    return self
  }

  private async send(body: Body = null): Promise<ApiResponse<T>> {
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

    const headers = { ...this.headers }
    let payload: BodyInit | null = null

    if (body != null && this.method !== 'GET') {
      if (body instanceof FormData) {
        delete headers['Content-Type']
        payload = body
      } else {
        payload = JSON.stringify(body)
      }
    }

    const res = await fetch(url, {
      method: this.method,
      headers: headers,
      body: payload,
    })

    if (res.status === 401 && this.path !== 'auth/refresh' && !this.retried) {
      this.retried = true
      ApiClient.refreshPromise ??= useAuth()
        .refreshSession()
        .finally(() => {
          ApiClient.refreshPromise = null
        })
      const ok = await ApiClient.refreshPromise
      if (ok) return await this.send(body)
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
