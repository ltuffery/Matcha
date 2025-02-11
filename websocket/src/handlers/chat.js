import {onlineUsers} from "../index.js";
import {Api} from "../services/api.js";
import {NOTIFICATION_TYPE} from "../enums/notification-types.js";

export const sendMessage = (username, socket) => {
  return async (to, content) => {
    const res = await Api.post(`/users/me/matches/${to}`)
      .header('Authorization', 'Bearer ' + socket.handshake.auth.token)
      .send({
        content: content
      })

    if (res.status !== 201) {
      return;
    }

    const data = await res.json()

    if (onlineUsers.has(to)) {
      socket.to(onlineUsers.get(to).socketId).emit("receive_message", data)
      socket.to(onlineUsers.get(to).socketId).emit("notification", {
        type: NOTIFICATION_TYPE.MESSAGE,
        data: data,
      })
    }

    data.isMe = true

    socket.emit("receive_message", data)
  }
}
