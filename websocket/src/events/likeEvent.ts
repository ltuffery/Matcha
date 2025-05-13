import {Socket} from "socket.io";
import {NOTIFICATION_TYPES} from "@/enums/notificationTypes";
import {sendRequest} from "@/events/notifications";

export const handleLikeEvent = (socket: Socket) => {
  socket.on("like", (username: string) => {
    sendRequest(username, NOTIFICATION_TYPES.LIKE, socket)
  })

  socket.on("unlike", (username: string) => {
    sendRequest(username, NOTIFICATION_TYPES.UNLIKE, socket)
  })
}
