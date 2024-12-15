import { Server } from "socket.io";
import { Api } from "./services/api.js";

const io = new Server({
  cors: {
    origin: '*'
  }
});

io.on("connection", (socket) => {
  socket.on("online", () => {
    Api
      .put('/users/me/status')
      .header('Authorization', socket.handshake.auth.token)
      .send({
        state: 1
      })
      .then(res => {
        if (res.status == 401) {
          console.log(socket.handshake.auth.token)
        }
      })
  })

  socket.on("disconnect", (reason) => {
    Api
      .put('/users/me/status')
      .header('Authorization', socket.handshake.auth.token)
      .send({
        state: 0
      })
  });
});

io.listen(3001);