import {MemoryCache} from "@/cache/memoryCache";

type OnlineUserType = {
  username: string,
  socketId: string,
}

export class OnlineUsersCache extends MemoryCache {
  static getAll(): OnlineUserType[] | undefined
  {
    return MemoryCache.get("onlineUsers")
  }

  static add(username: string, socketId: string): void
  {
    const cache = OnlineUsersCache.getAll()

    if (cache === undefined) {
      MemoryCache.set("onlineUsers", [
        {
          username: username,
          socketId: socketId,
        }
      ])
    } else {
      MemoryCache.set("onlineUsers", cache.concat([
        {
          username: username,
          socketId: socketId,
        }
      ]))
    }
  }

  static getSocketId(username: string): string | undefined
  {
    const cache = OnlineUsersCache.getAll()

    if (cache === undefined) {
      return undefined
    }

    return cache.find(user => user.username === username)?.socketId
  }

  static remove(username: string)
  {
    const cache = OnlineUsersCache.getAll()

    if (cache === undefined) return

    MemoryCache.set("onlineUsers", cache.filter(user => user.username != username))
  }
}
