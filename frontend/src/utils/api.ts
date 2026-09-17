import { logout } from '@/services/auth'

export class Api {
  method = ''
  path = ''
  headers: Record<string, string> = {
    'Content-Type': 'application/json',
  }

  static get(path: string): Api {
    return this.request('GET', path)
  }

  static delete(path: string): Api {
    return this.request('DELETE', path)
  }

  static post(path: string): Api {
    return this.request('POST', path)
  }

  static put(path: string): Api {
    return this.request('PUT', path)
  }

  static request(method: string, path: string): Api {
    const self = new this()

    self.method = method
    self.path = path.replace(/^\/+|\/+$/g, '')

    return self
  }

  header(name: string, value: string): this {
    this.headers[name] = value

    return this
  }

  async send(body: Record<string, unknown> | null = null): Promise<Response> {
    const jwt = localStorage.getItem('jwt')

    if (jwt != null) {
      this.header('Authorization', `Bearer ${jwt}`)
    }

    const port = location.port !== '' ? ':' + location.port : ''
    const res = await fetch(`http://${location.hostname}${port}/api/${this.path}`, {
      method: this.method,
      headers: this.headers,
      body: body != null ? JSON.stringify(body) : null,
    })

    if (res.status === 401) {
      logout()
    }

    return res
  }
}
