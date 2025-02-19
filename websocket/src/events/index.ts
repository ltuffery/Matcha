import { Server } from "socket.io";
import {OnlineUsersCache} from "@/cache/onlineUsersCache";
import {Api} from "@/services/api";
import {handleChatEvents} from "@/events/chatEvent";
import {handleLikeEvent} from "@/events/likeEvent";
import {handleViewEvent} from "@/events/viewEvent";

export const registerEvents = (io: Server) => {
  io.on("connection", (socket) => {
    const username: string = socket.handshake.auth.username;

    if (!username) {
      socket.disconnect()
      return;
    }

    OnlineUsersCache.add(username, socket.id)

    socket.emit("online_users", OnlineUsersCache.getAll()?.map(user => user.username))

    // Handlers
    handleChatEvents(socket)
    handleLikeEvent(socket)
    handleViewEvent(socket)

    // Disconnect event
    socket.on("disconnect", () => {
      OnlineUsersCache.remove(username)

      Api
        .post('/users/me/offline')
        .header('Authorization', 'Bearer ' + socket.handshake.auth.token)
        .send()
        .then(res => {
          if (res.ok)
            socket.broadcast.emit("user_offline", { username })
        })
    });
  });
};
