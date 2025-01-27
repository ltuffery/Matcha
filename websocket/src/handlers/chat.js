import {onlineUsers} from "../index.js";
import {Api} from "../services/api.js";

export const sendMessage = (username, socket) => {
  return async (to, content) => {
    if (onlineUsers.has(to)) {
      const res = await Api.post(`/users/me/matches/${to}`)
        .header('Authorization', 'Bearer ' + socket.handshake.auth.token)
        .send({
        content: content
      })

      if (res.status !== 201) {
        return;
      }

      const data = await res.json()

      socket.to(onlineUsers.get(to).socketId).emit("receive_message", data)

      data.isMe = true

      socket.emit("receive_message", data)
    }
  }
}
