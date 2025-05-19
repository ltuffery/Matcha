import { Server } from "socket.io";
import {OnlineUsersCache} from "@/cache/onlineUsersCache";
import {Api} from "@/services/api";
import {handleChatEvents} from "@/events/chatEvent";
import {handleLikeEvent} from "@/events/likeEvent";
import {handleViewEvent} from "@/events/viewEvent";
import {handleBrowsingEvent} from "@/events/browsingEvent";
import {cacheBrowsing} from "@/server";
import {db} from "@/database/database";

export const registerEvents = (io: Server) => {
  io.on("connection", (socket) => {
    const username: string = socket.handshake.auth.username;

    if (!username) {
      socket.disconnect()
      return;
    }

    OnlineUsersCache.add(username, socket.id)

    socket.emit("online_users", OnlineUsersCache.getAll()?.map(user => user.username))

    OnlineUsersCache.getAll()?.forEach((user) => {
      if (user.username === username) {
        return
      }

      console.log(username)

      socket.to(user.socketId).emit("user_online", username)
    })

    // Handlers
    handleChatEvents(socket)
    handleLikeEvent(socket)
    handleViewEvent(socket)
    handleBrowsingEvent(username, socket)

    // Disconnect event
    socket.on("disconnect", () => {
      OnlineUsersCache.remove(username)
      cacheBrowsing.delete(username)

      db.prepare("UPDATE users SET last_connection = ? WHERE username = ?", (err, stmt) => {
        if (err !== null) {
          console.log(err)
          return
        }

        stmt.execute([new Date(), username], (err, result) => {
          if (err == null) {
            socket.broadcast.emit("user_offline", username)
          }
        })
      })

      // Api
      //   .post('/users/me/offline')
      //   .header('Authorization', 'Bearer ' + socket.handshake.auth.token)
      //   .send()
      //   .then(res => {
      //     if (res.ok)
      //       socket.broadcast.emit("user_offline", { username })
      //   })
    });
  });
};
