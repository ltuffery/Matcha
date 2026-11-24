import { createServer } from "node:http";
import {Server, Socket} from "socket.io";
import { config } from "./config";
import { socketAuthMiddleware } from "./auth";
import { initRedisSubscriber } from "./redis";
import { isTypingTo, cleanIsTyping } from "./isTyping";
import * as presence from "./presence";

const httpServer = createServer();
const io = new Server(httpServer, {
	cors: {
		origin: config.frontOrigin,
		methods: ["GET", "POST"],
	},
});

presence.setNotifier((to, payload) => {
  io.to(`user:${to}`).emit("presence", payload);
});

io.use(socketAuthMiddleware);

io.on("connection", (socket) => {
  const me = socket.username
  socket.join(`user:${socket.username}`);
  console.log(`[socket] ${socket.username} joined room user:${socket.username} on socketId: ${socket.id}`);

  isTypingTo(io, socket);

  // Ask status
  presence.addConnection(me, socket.id);

  socket.on("presence:subscribe", async (target: unknown, ack: unknown) => {
    if (typeof target !== "string" || typeof ack !== "function") return;

    const res = presence.subscribe(me, target);
    ack(res.ok ? { userId: target, status: res.status } : { error: res.error });
  });

  socket.on("presence:unsubscribe", (target: unknown) => {
    if (typeof target === "string") presence.unsubscribe(me, target);
  });


  // Client disconnect
	socket.on("disconnect", () => {
    console.log(`[socket] disconnected: ${socket.id}`);
    cleanIsTyping(socket.id);
    presence.removeConnection(me, socket.id)
	});
});

initRedisSubscriber(io);

httpServer.listen(config.port, () => {
	console.log(`[ws] listening on :${config.port}`);
});
