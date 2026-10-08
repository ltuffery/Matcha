import { useAuth } from '@/composable/useAuth'

export class ApiClient<T> {
  method = ''
  path = ''
  headers: Record<string, string> = {
    'Content-Type': 'application/json',
  }

  static get<T>(
    path: string,
    body: Record<string, unknown> | null = null,
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

  private async send(body: Record<string, any> | null = null): Promise<ApiResponse<T>> {
    const jwt = localStorage.getItem('jwt')

    if (jwt != null) {
      this.header('Authorization', `Bearer ${jwt}`)
    }

    const port = location.port !== '' ? ':' + location.port : ''
    const res = await fetch(
      `http://${location.hostname}${port}/api/${this.path}`,
      {
        method: this.method,
        headers: this.headers,
        body: body != null ? JSON.stringify(body) : null,
      },
    )

    if (res.status === 401) {
      useAuth().logout()
    }

    return new ApiResponse<T>(res)
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
