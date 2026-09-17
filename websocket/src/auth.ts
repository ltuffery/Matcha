import jwt from "jsonwebtoken";
import type { Socket } from "socket.io";
import { config } from "./config";

declare module "socket.io" {
  interface Socket {
    username: string;
  }
}

interface JwtPayload {
  username: string;
  exp: number;
  iat: number;
}

export function socketAuthMiddleware(
  socket: Socket,
  next: (err?: Error) => void
) {
  const token = socket.handshake.auth?.token;

  if (!token || typeof token !== "string") {
    return next(new Error("AUTH_REQUIRED"));
  }

  try {
    const payload = jwt.verify(token, config.jwtSecret) as JwtPayload;

    if (!payload.username) {
      return next(new Error("AUTH_INVALID_PAYLOAD"));
    }

    socket.username = payload.username;
    return next();
  } catch (err) {
    return next(new Error("AUTH_INVALID"));
  }
}