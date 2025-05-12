import { Server } from "socket.io";
import authMiddleware from "@/middlewares/authMiddleware";
import {registerEvents} from "@/events";

export const cacheBrowsing = new Map()

const io = new Server({
  cors: {
    origin: '*'
  }
});

io.use(authMiddleware)

registerEvents(io)

io.listen(3001);
