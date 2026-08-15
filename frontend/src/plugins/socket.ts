import { io, type Socket } from 'socket.io-client'
import { getToken, isAuthenticated } from '@/services/auth'
import { useOnlineUsersStore } from '@/store/onlineUsers'
import type { JwtPayload } from '@/types'

let socket: Socket | null = null

export async function connectSocket(): Promise<void> {
  if (socket) return

  const authed = await isAuthenticated()
  if (!authed) return

  const token = getToken()
  if (!token) return

  const decoded = JSON.parse(atob(token.split('.')[1])) as JwtPayload

  socket = io(`${location.hostname}/ws`, {
    auth: {
      username: decoded.username,
      token: token,
    },
  })

  socket.on('connect', () => {
    const onlineUsersStore = useOnlineUsersStore()

    socket!.on('online_users', (users: string[]) => {
      onlineUsersStore.setOnlineUsers(users)
    })

    socket!.on('user_online', (username: string) => {
      if (!onlineUsersStore.isOnlineUser(username))
        onlineUsersStore.addOnlineUser(username)
    })

    socket!.on('user_offline', (username: string) => {
      onlineUsersStore.removeOnlineUser(username)
    })
  })

  socket.on('disconnect', () => {})
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
