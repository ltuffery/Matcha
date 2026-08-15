export interface User {
  username: string
  email?: string
  first_name?: string
  last_name?: string
  gender?: 'M' | 'F' | 'O'
  biography?: string
  birthday?: string
  age?: number
  photos?: string[]
  tags?: string[]
  avatar?: string
  distance?: number
  fame_rating?: number
  me?: boolean
  last_message?: MessageData
  unread?: number
  preferences?: Preferences
}

export interface MessageData {
  content: string
  sender: string
  isMe?: boolean
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

export interface JwtPayload {
  username: string
  exp: number
}

export interface LoginResponse {
  token: string
  refresh: string
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
