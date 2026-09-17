import Redis from "ioredis";
import type { Server } from "socket.io";
import { config } from "./config";

interface AppEvent {
  type: string;
  target_username: string;
  payload: unknown;
}

function isValidAppEvent(data: unknown): data is AppEvent {
  return (
    typeof data === "object" &&
    data !== null &&
    typeof (data as AppEvent).type === "string" &&
    typeof (data as AppEvent).target_username === "string"
  );
}

export function initRedisSubscriber(io: Server) {
  const redisSub = new Redis(config.redisUrl);

  redisSub.on("error", (err) => {
    console.error("[redis] connection error", err);
  });

  redisSub.subscribe("app_events", (err) => {
    if (err) {
      console.error("[redis] subscribe failed", err);
    } else {
      console.log("[redis] subscribed to app_events");
    }
  });

  redisSub.on("message", (channel, message) => {
    if (channel !== "app_events") return;

    let event: unknown;
    try {
      event = JSON.parse(message);
    } catch (err) {
      console.error("[redis] invalid JSON payload:", message);
      return;
    }

    if (!isValidAppEvent(event)) {
      console.error("[redis] malformed event:", event);
      return;
    }

    io.to(`user:${event.target_username}`).emit(event.type, event.payload);
  });

  return redisSub;
}