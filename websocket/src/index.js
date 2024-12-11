import { Server } from "socket.io";

const io = new Server({
  cors: {
    origin: '*'
  }
});

io.on("connection", (socket) => {
  socket.on("test", () => {
    console.log("test on : ", socket.handshake.auth.token)
  })

  socket.on("disconnect", (reason) => {
    console.log("deco", reason)
  });
});

io.listen(3001);