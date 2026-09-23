import type { Server, Socket } from "socket.io";

const lastTypingAt = new Map<string, number>();
const TYPING_RATE_LIMIT_MS = 2000;

export function isTypingTo(io: Server, socket: Socket) {
  socket.on("typing", (data: { to_username: string }) => {
    if (!data?.to_username) return;

    console.log(`[typing] from=${socket.username} to=${data?.to_username}`);
    const now = Date.now();
    const last = lastTypingAt.get(socket.id) ?? 0;

    if (now - last < TYPING_RATE_LIMIT_MS) {
      return;
    }
    lastTypingAt.set(socket.id, now);

    console.log(`[typing] emitting to room user:${data.to_username}`);
    io.to(`user:${data.to_username}`).emit("typing", {
      from_username: socket.username,
    });
  });

  socket.on("stop_typing", (data: { to_username: string }) => {
    if (!data?.to_username) return;

    io.to(`user:${data.to_username}`).emit("stop_typing", {
      from_username: socket.username,
    });
  });
}

export function cleanIsTyping(socketId: any) {
  lastTypingAt.delete(socketId);
}
