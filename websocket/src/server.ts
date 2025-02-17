import { Server } from "socket.io";
import authMiddleware from "@/middlewares/authMiddleware";
import {sendMessage} from "@/handlers/chat";
import {browse} from "@/handlers/browsing";
import {sortProfile} from "@/handlers/sort-profile";
import {like, unlike, view} from "@/handlers/notifications";
import {Api} from "@/services/api";

export const onlineUsers = new Map()
export const cacheBrowsing = new Map()

const io = new Server({
  cors: {
    origin: '*'
  }
});

io.use(authMiddleware)

io.on("connection", (socket) => {
  const username: string = socket.handshake.auth.username;

  if (!username) {
    socket.disconnect()
    return;
  }

  onlineUsers.set(username, { socketId: socket.id })

  socket.emit("online_users", Array.from(onlineUsers.keys()))

  socket.broadcast.emit("user_online", { username })

  /** Handlers */
  socket.on("send_message", sendMessage(username, socket))
  socket.on("browsing", browse(username, socket))
  socket.on("filter", sortProfile(username, socket))
  socket.on("view", view(username, socket))
  socket.on("like", like(username, socket))
  socket.on("unlike", unlike(username, socket))

  socket.on("disconnect", (reason) => {
    onlineUsers.delete(username)
    cacheBrowsing.delete(username)

    Api
      .post('/users/me/offline')
      .header('Authorization', 'Bearer ' + socket.handshake.auth.token)
      .send()

    socket.broadcast.emit("user_offline", { username })
  });
});

io.listen(3001);
