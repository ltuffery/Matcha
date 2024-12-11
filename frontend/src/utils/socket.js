import { io } from 'socket.io-client' 

export const socket = io('localhost:3001', {
  auth: {
    token: localStorage.getItem("token")
  }
})