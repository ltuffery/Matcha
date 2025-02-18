import {NOTIFICATION_TYPES} from "@/enums/notificationTypes";
import {Api} from "@/services/api";
import {Socket} from "socket.io";
import {OnlineUsersCache} from "@/cache/onlineUsersCache";

const sendNotification = async (type: NOTIFICATION_TYPES, username: string, socket: Socket) => {
  const res = await Api.post(`/users/${username}/notifications`).header('Authorization', 'Bearer ' + socket.handshake.auth.token).send({
    type: type,
  })
  const data = await res.json()

  if (OnlineUsersCache.has(username)) {
    socket.to(OnlineUsersCache.getSocketId(username) as string).emit("notification", data)
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
