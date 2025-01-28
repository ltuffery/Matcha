import { logout } from '@/services/auth'

export class Api {
  method = ''
  headers = {
    'Content-Type': 'application/json',
  }

  /**
   *
   * @param path
   * @returns {Api}
   */
  static get(path) {
    return this.request('GET', path)
  }

  /**
   *
   * @param path
   * @returns {Api}
   */
  static delete(path) {
    return this.request('DELETE', path)
  }

  /**
   *
   * @param path
   * @returns {Api}
   */
  static post(path) {
    return this.request('POST', path)
  }

  /**
   *
   * @param path
   * @returns {Api}
   */
  static put(path) {
    return this.request('PUT', path)
  }

  /**
   *
   * @param path
   * @returns {Api}
   */
  static request(method, path) {
    let self = new this()

    self.method = method
    self.path = path.replace(/^\/+|\/+$/g, '')

    return self
  }

  header(name, value) {
    this.headers[name] = value

    return this
  }

  async send(body = null) {
    const jwt = localStorage.getItem('jwt')

    if (jwt != null) {
      this.header('Authorization', `Bearer ${jwt}`)
    }

    let res = await fetch(`http://${location.hostname}:3000/${this.path}`, {
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
