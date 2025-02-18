import {onlineUsers} from "@/server";
import {NOTIFICATION_TYPES} from "@/enums/notificationTypes";
import {Api} from "@/services/api";
import {Socket} from "socket.io";

const sendNotification = async (type: NOTIFICATION_TYPES, username: string, socket: Socket) => {
  const res = await Api.post(`/users/${username}/notifications`).header('Authorization', 'Bearer ' + socket.handshake.auth.token).send({
    type: type,
  })
  const data = await res.json()

  if (onlineUsers.has(username)) {
    socket.to(onlineUsers.get(username).socketId).emit("notification", data)
  }
}

export const sendRequest = (username: string, type: NOTIFICATION_TYPES, socket: Socket) => {
  Api.post(`/users/${username}/${type.toLowerCase()}`)
    .header('Authorization', 'Bearer ' + socket.handshake.auth.token)
    .send()
    .then(async res => {
      if (res.ok) {
        await sendNotification(NOTIFICATION_TYPES.LIKE, username, socket)
      }
    })
}

export const like = (username: string, socket: Socket) => {
  return async (user: string) => {
    Api.post(`/users/${user}/like`)
      .header('Authorization', 'Bearer ' + socket.handshake.auth.token)
      .send()
      .then(async res => {
        if (res.ok) {
          await sendNotification(NOTIFICATION_TYPES.LIKE, user, socket)
        }
      })
  }
}

export const unlike = (username: string, socket: Socket) => {
  return async (user: string) => {
    Api.post(`/users/${user}/unlike`)
      .header('Authorization', 'Bearer ' + socket.handshake.auth.token)
      .send()
      .then(async res => {
        if (res.ok) {
          await sendNotification(NOTIFICATION_TYPES.UNLIKE, user, socket)
        }
      })
  }
}

export const view = (username: string, socket: Socket) => {
  return async (user: string) => {
    Api.post(`/users/${user}/view`).header('Authorization', 'Bearer ' + socket.handshake.auth.token).send()

    await sendNotification(NOTIFICATION_TYPES.VIEW, user, socket)
  }
}
