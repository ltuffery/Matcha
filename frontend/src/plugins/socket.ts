import { io, type Socket } from 'socket.io-client'
import { getToken, isAuthenticated } from '@/services/auth'
import { useOnlineUsersStore } from '@/store/onlineUsers'
import type { JwtPayload, MessageData } from '@/types'
import { useTypingStore } from '@/store/isTyping'
import { useMessagesStore } from '@/store/messages'

let socket: Socket | null = null

export async function connectSocket(): Promise<void> {
  if (socket) return

  const authed = await isAuthenticated()
  if (!authed) return

  const token = getToken()
  if (!token) return

  socket = io(location.origin, {
    path: "/ws/socket.io/",
    auth: { token: token },
  })

  socket.on('connect', () => {
    // const onlineUsersStore = useOnlineUsersStore()
    console.log("WS Connected");
    const typingStore = useTypingStore();
    const messageStore = useMessagesStore();

    socket!.on("typing", ({ from_username }) => {
      typingStore.addTypingUser(from_username);
    })

    socket!.on("stop_typing", ({ from_username }) => {
      typingStore.stopTyping(from_username);
    })

    socket!.on("new_message", (message: MessageData) => {
      console.log("message recived :", message);
      messageStore.addMessage(message.sender, message);
    })

    // socket!.on('online_users', (users: string[]) => {
    //   onlineUsersStore.setOnlineUsers(users)
    // })

    // socket!.on('user_online', (username: string) => {
    //   if (!onlineUsersStore.isOnlineUser(username))
    //     onlineUsersStore.addOnlineUser(username)
    // })

    // socket!.on('user_offline', (username: string) => {
    //   onlineUsersStore.removeOnlineUser(username)
    // })
  })

  socket.on('disconnect', () => {})
socket.on("connect_error", (err) => console.log("erreur:", err.message));
}


export function getSocket(): Socket {
  if (!socket) {
    console.error("Uninitialized socket, call 'connectSocket' first")
  }
  return socket as Socket
}

export function disconnectSocket(): void {
  if (socket) {
    socket.disconnect()
    socket = null
  }
}
