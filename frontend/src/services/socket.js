import { io } from 'socket.io-client' 

export let socket = io('localhost:3001', {
  auth: {
    token: localStorage.getItem("jwt")
  }
})

export const refreshSocket = () => {
  socket = io('localhost:3001', {
    auth: {
      token: localStorage.getItem("jwt")
    }
  })
}