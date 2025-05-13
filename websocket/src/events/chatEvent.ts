import {sendNotification} from "./notifications.js";
import {NOTIFICATION_TYPES} from "@/enums/notificationTypes";
import {Socket} from "socket.io";
import {OnlineUsersCache} from "@/cache/onlineUsersCache";
import {Api} from "@/services/api";

export const handleChatEvents = (socket: Socket) => {
  socket.on("send_message", async (to, content) => {
    const res = await Api.post(`/users/me/matches/${to}`)
      .header('Authorization', 'Bearer ' + socket.handshake.auth.token)
      .send({
        content: content
      })

    if (res.status !== 201) {
      return;
    }

    const data = await res.json()

    if (OnlineUsersCache.has(to)) {
      socket.to(OnlineUsersCache.getSocketId(to) as string).emit("receive_message", data)

      await sendNotification(NOTIFICATION_TYPES.MESSAGE, to, socket)
    }

    data.isMe = true

    socket.emit("receive_message", data)
  })
}
