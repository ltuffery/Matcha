import {onlineUsers} from "../index.js";
import {NOTIFICATION_TYPE} from "../enums/notification-types.js";
import {Api} from "../services/api.js";

export const like = (username, socket) => {
  return (user) => {
    Api.post(`/users/${user}/like`).send()

    socket.to(onlineUsers.get(user).socketId).emit("notification", {
      type: NOTIFICATION_TYPE.LIKE,
      data: {
        username: user,
      },
    })
  }
}

export const unlike = (username, socket) => {
  return (user) => {
    Api.post(`/users/${user}/unlike`).send()

    socket.to(onlineUsers.get(user).socketId).emit("notification", {
      type: NOTIFICATION_TYPE.UNLIKE,
      data: {
        username: user,
      },
    })
  }
}

export const view = (username, socket) => {
  return (user) => {
    Api.post(`/users/${user}/view`).send()

    socket.to(onlineUsers.get(user).socketId).emit("notification", {
      type: NOTIFICATION_TYPE.VIEW,
      data: {
        username: user,
      },
    })
  }
}
