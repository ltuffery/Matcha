import {onlineUsers} from "../index.js";
import {NOTIFICATION_TYPE} from "../enums/notification-types.js";
import {Api} from "../services/api.js";

export const sendNotification = async (type, username, socket) => {
  const res = await Api.post(`/users/${username}/notifications`).header('Authorization', 'Bearer ' + socket.handshake.auth.token).send({
    type: type,
  })
  const data = await res.json()

  if (onlineUsers.has(username)) {
    socket.to(onlineUsers.get(username).socketId).emit("notification", data)
  }
}

export const like = (username, socket) => {
  return async (user) => {
    Api.post(`/users/${user}/like`).header('Authorization', 'Bearer ' + socket.handshake.auth.token).send()

    await sendNotification(NOTIFICATION_TYPE.LIKE, user, socket)
  }
}

export const unlike = (username, socket) => {
  return async (user) => {
    Api.post(`/users/${user}/unlike`).header('Authorization', 'Bearer ' + socket.handshake.auth.token).send()

    await sendNotification(NOTIFICATION_TYPE.UNLIKE, user, socket)
  }
}

export const view = (username, socket) => {
  return async (user) => {
    Api.post(`/users/${user}/view`).header('Authorization', 'Bearer ' + socket.handshake.auth.token).send()

    await sendNotification(NOTIFICATION_TYPE.VIEW, user, socket)
  }
}
