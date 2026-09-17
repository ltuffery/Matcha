import type { Server, Socket } from "socket.io";
import { config } from "./config";

interface SendMessagePayload {
  to_username: string;
  content: string;
}

interface AckResponse {
  ok: boolean;
  message?: unknown;
  error?: string;
}

export function registerSocketHandlers(io: Server, socket: Socket) {
  socket.on(
    "send_message",
    async (data: SendMessagePayload, ack?: (res: AckResponse) => void) => {
      if (!data?.to_username || !data?.content) {
        ack?.({ ok: false, error: "MISSING_FIELDS" });
        return;
      }

      try {
        const res = await fetch(`${config.apiInternalUrl}/internal/messages`, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-Internal-Secret": config.internalSecret,
          },
          body: JSON.stringify({
            from_username: socket.username,
            to_username: data.to_username,
            content: data.content,
          }),
        });

        if (!res.ok) {
          const errBody = await res.json().catch(() => ({}));
          ack?.({ ok: false, error: errBody.error ?? "API_ERROR" });
          return;
        }

        const message = await res.json();

        // confirmation au sender (avec l'id / timestamp définitifs créés par l'API)
        ack?.({ ok: true, message });

        // push direct au destinataire s'il est connecté ; sinon il le verra
        // via un fetch REST classique à sa prochaine connexion (l'API a déjà
        // persisté le message, la DB reste la source de vérité)
        io.to(`user:${data.to_username}`).emit("new_message", message);
      } catch (err) {
        console.error("[send_message] error", err);
        ack?.({ ok: false, error: "INTERNAL_ERROR" });
      }
    }
  );
}
