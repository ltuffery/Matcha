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

// Use middleware to verify token (if is connected)
io.use(socketAuthMiddleware);

io.on("connection", (socket) => {
  socket.join(`user:${socket.username}`);
  console.log(`[socket] ${socket.username} joined room user:${socket.username} on socketId: ${socket.id}`);

  // Listen typing and stop typing (in chat msg)
  isTypingTo(io, socket);

  // Client disconnect
	socket.on("disconnect", () => {
    console.log(`[socket] disconnected: ${socket.id}`);
    cleanIsTyping(socket.id);
	});
});

// Init and subscribe to chall redis (API -> WS)
// Verify and redistribute the redis message to user
initRedisSubscriber(io);

httpServer.listen(config.port, () => {
	console.log(`[ws] listening on :${config.port}`);
});
