import { getToken, isAuthenticated } from '@/services/auth.js'
import { io } from 'socket.io-client'
import { useOnlineUsersStore } from '@/store/onlineUsers.js'

let socket = null

export function connectSocket() {
  if (!socket && isAuthenticated()) {
    const decoded = JSON.parse(atob(getToken().split('.')[1]))

    socket = io(`${location.hostname}/ws`, {
      auth: {
        username: decoded.username,
        token: getToken(),
      },
    })

    socket.on('connect', () => {
      const onlineUsersStore = useOnlineUsersStore()

      socket.on('online_users', users => {
        onlineUsersStore.setOnlineUsers(users)
      })

      socket.on('user_online', (username) => {
        console.log(username)
        if (!onlineUsersStore.isOnlineUser(username))
          onlineUsersStore.addOnlineUser(username)
      })

      socket.on('user_offline', (username) => {
        onlineUsersStore.removeOnlineUser(username)
      })
    })

    socket.on('disconnect', () => {})
  }
}

export function getSocket() {
  if (!socket) {
    console.error("Uninitialized socket, call 'connectedSocket' first")
  }
  return socket
}

export function disconnectSocket() {
  if (socket) {
    socket.disconnect()
    socket = null
  }
}
