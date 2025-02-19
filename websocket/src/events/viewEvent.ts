import {Socket} from "socket.io";
import {sendRequest} from "@/events/notifications";
import {NOTIFICATION_TYPES} from "@/enums/notificationTypes";

export const handleViewEvent = (socket: Socket) => {
  socket.on("view", (username: string) => {
    sendRequest(username, NOTIFICATION_TYPES.VIEW, socket)
  })
}
