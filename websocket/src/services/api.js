export class Api {
  method = ''
  headers = {
    'Content-Type': 'application/json',
  }

  static get(path) {
    return this.request('GET', path)
  }

  static post(path) {
    return this.request('POST', path)
  }

  static put(path) {
    return this.request('PUT', path)
  }

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

  async send(body) {
    let res = await fetch(`http://api:3000/${this.path}`, {
      method: this.method,
      headers: this.headers,
      body: JSON.stringify(body),
    })

    return res
  }
  }
