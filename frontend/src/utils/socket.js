import { io } from 'socket.io-client' 

const socket = io('localhost:3001', {
  auth: {
    token: "abc"
  }
})

socket.emit("test")