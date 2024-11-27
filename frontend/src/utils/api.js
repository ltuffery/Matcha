export class Api {
  method = ''

  static post(path) {
    let self = new this()

    self.method = 'POST'
    self.path = path.replace(/^\/+|\/+$/g, '')

    return self
  }

  async send(body) {

    let req = await fetch(`http://localhost:3000/${this.path}`, {
      method: this.method,
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(body),
    })

    return await req.json()
  }
}
