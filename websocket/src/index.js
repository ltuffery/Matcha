import { Server } from "socket.io";
import authMiddleware from "./middlewares/authMiddleware.js";
import {sendMessage} from "./handlers/chat.js";

export const onlineUsers = new Map()
const io = new Server({
  cors: {
    origin: '*'
  }
});

io.use(authMiddleware)

io.on("connection", (socket) => {
  const username = socket.handshake.auth.username;

  if (!username) {
    socket.disconnect()
    return;
  }

  onlineUsers.set(username, { socketId: socket.id })

  socket.emit("online_users", Array.from(onlineUsers.keys()))

  socket.broadcast.emit("user_online", { username })

  /** Handlers */
  socket.on("send_message", sendMessage(username, socket))

  socket.on("disconnect", (reason) => {
    onlineUsers.delete(username)

    socket.broadcast.emit("user_offline", { username })
  });
});

io.listen(3001);
