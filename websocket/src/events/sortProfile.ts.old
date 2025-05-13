import {cacheBrowsing} from "@/server";
import {Socket} from "socket.io";

type Rules = {[name: string]: boolean}

export const sortProfile = (username: string, socket: Socket) => {
  return (rules: Rules) => {
    if (!cacheBrowsing.has(username)) {
      cacheBrowsing.set(username, {
        sortBy: rules,
        profiles: []
      })
    }

    const user = cacheBrowsing.get(username)

    const profileSorted = user.profiles.sort((a, b) => {
      return (user.sortBy.includes('age') ? b.age - a.age : 0)
        + (user.sortBy.includes('distance') ? b.distance - a.distance : 0)
        + (user.sortBy.includes('fame_rating') ? b.fame_rating - a.fame_rating : 0)
        + (user.sortBy.includes('common_tags') ? b.common_tags - a.common_tags : 0)
    })

    cacheBrowsing.set(username, {
      sortBy: user.sortBy,
      profiles: profileSorted,
    })
  }
}
