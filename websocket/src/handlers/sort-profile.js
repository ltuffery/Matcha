import {cacheBrowsing} from "../index.js";

export const sortProfile = (username, socket) => {
  return (rules) => {
    if (!cacheBrowsing.has(username)) {
      cacheBrowsing.set(username, {
        sortBy: rules,
        profiles: []
      })
    }

    const user = cacheBrowsing.get(username)

    const profileSorted = user.profiles.sort((a, b) => {
      return (user.sortBy.includes('age') ?? b.age - a.age)
        + (user.sortBy.includes('location') ?? b.location - a.location)
        + (user.sortBy.includes('fame_rating') ?? b.fame_rating - a.fame_rating)
        + (user.sortBy.includes('tags') ?? b.common_tag - a.common_tag)
    })

    cacheBrowsing.set(username, {
      sortBy: user.sortBy,
      profiles: profileSorted,
    })
  }
}
