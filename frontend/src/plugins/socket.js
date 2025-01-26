import {getToken, isAuthenticated} from "@/services/auth.js";
import {io} from "socket.io-client";
import {useOnlineUsersStore} from "@/store/onlineUsers.js";

let socket = null;

export function connectSocket() {
  if (!socket && isAuthenticated()) {
    const decoded = JSON.parse(atob(getToken().split('.')[1]))

    socket = io(`${location.hostname}:3001`, {
      auth: {
        username: decoded.username,
        token: getToken(),
      },
    })

    socket.on("connect", () => {
      const onlineUsersStore = useOnlineUsersStore()

      socket.on("online_users", (users) => {
        onlineUsersStore.setOnlineUsers(users);
      });

      socket.on("user_online", ({ username }) => {
        onlineUsersStore.addOnlineUser(username);
        console.log("new user online")
      });

      socket.on("user_offline", ({ username }) => {
        onlineUsersStore.removeOnlineUser(username);
      });
    });

    socket.on("disconnect", () => {
    });
  }
}

export function getSocket() {
  if (!socket) {
    console.error("Socket non initialisé. Appelez connectSocket d'abord.");
  }
  return socket;
}

export function disconnectSocket() {
  if (socket) {
    socket.disconnect();
    socket = null;
  }
}
