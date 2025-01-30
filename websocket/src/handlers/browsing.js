import {cacheBrowsing} from "../index.js";
import {Api} from "../services/api.js";

let finished = []

export const browse = (username, socket) => {
  return async () => {
    if (finished.includes(username)) {
      if (!cacheBrowsing.has(username)) {
        finished = finished.filter(value => value !== username)
      } else {
        socket.emit("browsing", null)
        return
      }
    }

    if (!cacheBrowsing.has(username) || cacheBrowsing.get(username).length === 0) {
      const res = await Api
        .get('/users/me/suggestions')
        .header('Authorization', socket.handshake.auth.token)
        .send()

      const data = await res.json()

      if (!cacheBrowsing.has(username))
        cacheBrowsing.set(username, {
          sortBy: [],
          profiles: data
        })
    }

    if (cacheBrowsing.has(username) && cacheBrowsing.get(username).length > 0) {
      const users = cacheBrowsing.get(username)
      const suggest = users.profiles[0]

      cacheBrowsing.set(username, {
        sortBy: users.sortBy,
        profiles: users.profiles.slice(1, users.length)
      })

      socket.emit("browsing", suggest)

      if (cacheBrowsing.get(username).length === 0) {
        finished.push(username)
      }
    }
  }
}
