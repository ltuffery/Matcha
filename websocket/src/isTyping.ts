import type { Server, Socket } from "socket.io";

const lastTypingAt = new Map<string, number>();
const TYPING_RATE_LIMIT_MS = 2000;

export function isTypingTo(io: Server, socket: Socket) {
  // ### New typing event ###
  socket.on("typing", (data: { to_username: string }) => {
    if (!data?.to_username) return;

    // Check limit to avoid 'is typing' spam
    const now = Date.now();
    const last = lastTypingAt.get(socket.id) ?? 0;
    if (now - last < TYPING_RATE_LIMIT_MS) {
      return;
    }

    lastTypingAt.set(socket.id, now);

    // Send typing ping to user target
    io.to(`user:${data.to_username}`).emit("typing", {from_username: socket.username});
  });

  // ### Stop typing event ###
  socket.on("stop_typing", (data: { to_username: string }) => {
    if (!data?.to_username) return;

    // Send stop typing to user target
    io.to(`user:${data.to_username}`).emit("stop_typing", {from_username: socket.username});
  });
}

// clean from id list (on disconect)
export function cleanIsTyping(socketId: any) {
  lastTypingAt.delete(socketId);
}
