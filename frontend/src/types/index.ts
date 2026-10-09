export interface SmallUser {
  avatar: string
  username: string
  first_name: string
  last_name: string
}

export interface MessageData {
  sender: string
  avatar: string
  content: string
  view: boolean
  created_at: string
}

export interface MessagesResponse {
  messages: MessageData[]
}

export interface NotificationData {
  id: string | number
  data: {
    username: string
    avatar: string
    content: string
    created_at: string
    view: boolean
  }
}

export interface Preferences {
  age_minimum: number
  age_maximum: number
  distance_maximum: number
  sexual_preferences: string
  by_tags: boolean
  lat: number
  lon: number
  is_custom_loc: number
}

export interface GeoPositionInfo {
  countryCode: string
  name: string
}

export interface CityInfo {
  lat: number
  lng: number
  toponymName: string
}
