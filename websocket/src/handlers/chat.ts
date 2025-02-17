import {onlineUsers} from "@/server";
import {Api} from "@/services/api";
import {sendNotification} from "./notifications.js";
import {NOTIFICATION_TYPE} from "@/enums/notification-types";
import {Socket} from "socket.io";

export const sendMessage = (username: string, socket: Socket) => {
  return async (to: string, content: string) => {
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
      await sendNotification(NOTIFICATION_TYPE.MESSAGE, to, socket)
    }

    data.isMe = true

    socket.emit("receive_message", data)
  }
}
