import {onlineUsers} from "@/server";
import {NOTIFICATION_TYPE} from "@/enums/notification-types";
import {Api} from "@/services/api";
import {Socket} from "socket.io";

export const sendNotification = async (type: NOTIFICATION_TYPE, username: string, socket: Socket) => {
  const res = await Api.post(`/users/${username}/notifications`).header('Authorization', 'Bearer ' + socket.handshake.auth.token).send({
    type: type,
  })
  const data = await res.json()

  if (onlineUsers.has(username)) {
    socket.to(onlineUsers.get(username).socketId).emit("notification", data)
  }
}

export const like = (username: string, socket: Socket) => {
  return async (user: string) => {
    Api.post(`/users/${user}/like`).header('Authorization', 'Bearer ' + socket.handshake.auth.token).send()

    await sendNotification(NOTIFICATION_TYPE.LIKE, user, socket)
  }
}

export const unlike = (username: string, socket: Socket) => {
  return async (user: string) => {
    Api.post(`/users/${user}/unlike`).header('Authorization', 'Bearer ' + socket.handshake.auth.token).send()

    await sendNotification(NOTIFICATION_TYPE.UNLIKE, user, socket)
  }
}

export const view = (username: string, socket: Socket) => {
  return async (user: string) => {
    Api.post(`/users/${user}/view`).header('Authorization', 'Bearer ' + socket.handshake.auth.token).send()

    await sendNotification(NOTIFICATION_TYPE.VIEW, user, socket)
  }
}
