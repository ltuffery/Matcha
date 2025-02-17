type Method = 'PUT' | 'POST' | 'GET' | 'PATH' | 'DELETE'
type Headers = {[name: string]: string}
type Body = {[field: string]: any}

export class Api {
  _method: Method|'' = ''
  _path: string = ''
  _headers: Headers = {
    'Content-Type': 'application/json',
  }

  static get(path: string): Api
  {
    return this.request('GET', path)
  }

  static post(path: string): Api
  {
    return this.request('POST', path)
  }

  static put(path: string) {
    return this.request('PUT', path)
  }

  static request(method: Method, path: string): Api
  {
    let self = new this()

    self._method = method
    self._path = path.replace(/^\/+|\/+$/g, '')

    return self
  }

  header(name: string, value: string): this
  {
    this._headers[name] = value

    return this
  }

  async send(body: Body|unknown) {
    return await fetch(`http://api:3000/${this._path}`, {
      method: this._method,
      headers: this._headers,
      body: JSON.stringify(body),
    })
  }
  }
