import { useAuth } from '@/composable/useAuth'

export class ApiClient<T> {
  method = ''
  path = ''
  headers: Record<string, string> = {
    'Content-Type': 'application/json',
  }

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

    if (res.status === 401) {
      await useAuth().refreshSession()
      return await this.send(body)
    }

    return new ApiResponse<T>(res)
  }

  private toQueryString(params: Record<string, any>): string {
    const search = new URLSearchParams()

    for (const [key, value] of Object.entries(params)) {
      // On ignore les valeurs vides
      if (value === null || value === undefined || value === '') continue

      // Les tableaux deviennent "a,b,c" (ce que votre backend attend avec explode(','))
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
  constructor(private response: Response) {
  }

  async json(): Promise<T> {
    return await this.response.json()
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
