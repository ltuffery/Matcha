import { io } from 'socket.io-client'

export let socket = io(`${location.hostname}:3001`, {
  auth: {
    token: localStorage.getItem('jwt'),
  },
})

export const refreshSocket = () => {
  socket = io(`${location.hostname}:3001`, {
    auth: {
      token: localStorage.getItem('jwt'),
    },
  })
}
