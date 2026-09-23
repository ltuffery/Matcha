import { createServer } from "node:http";
import { Server } from "socket.io";
import { config } from "./config";
import { socketAuthMiddleware } from "./auth";
import { initRedisSubscriber } from "./redis";
import { registerSocketHandlers } from "./socketHandlers";
import { isTypingTo, cleanIsTyping } from "./isTyping";

const httpServer = createServer();

const io = new Server(httpServer, {
	cors: {
		origin: config.frontOrigin,
		methods: ["GET", "POST"],
	},
});

io.use(socketAuthMiddleware);

io.on("connection", (socket) => {
	// console.log(`[socket] connected: ${socket.id}`);
  socket.join(`user:${socket.username}`);
  console.log(`[socket] ${socket.username} joined room user:${socket.username} on socketId: ${socket.id}`);
	// registerSocketHandlers(io, socket);
  isTypingTo(io, socket);

	socket.on("disconnect", () => {
    console.log(`[socket] disconnected: ${socket.id}`);
    cleanIsTyping(socket.id);
	});
});

initRedisSubscriber(io);

httpServer.listen(config.port, () => {
	console.log(`[ws] listening on :${config.port}`);
});
