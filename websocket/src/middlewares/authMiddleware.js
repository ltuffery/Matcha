import {Api} from "../services/api.js";

export default async function authMiddleware(socket, next) {
  const token = socket.handshake.auth.token; // Récupère le token envoyé par le client
  if (!token) {
    return next(new Error("Token required"));
  }

  try {
    const response = await Api.post('/auth/verify-token').send({token: token})

    if (response.ok) {
      next();
    } else {
      next(new Error("Token invalide"));
    }
  } catch (error) {
    next(new Error("Erreur lors de la vérification du token"));
  }
}
