import {Api} from "@/services/api";
import {Socket} from "socket.io";

export default async function authMiddleware(socket: Socket, next: CallableFunction) {
  const token = socket.handshake.auth.token; // Récupère le token envoyé par le client
  if (!token) {
    return next(new Error("Token required"));
  }

  try {
    const response = await Api.post('/auth/verify-token').send({token: token})

    if (response.ok) {
      next();
    } else {
      next(new Error("Token wrong"));
    }
  } catch (error) {
    next(new Error("Error while verification token"));
  }
}
