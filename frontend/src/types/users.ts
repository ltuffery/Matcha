export interface User {
  username: string | null
  email: string | null
  password: string | null
  birthday: string | null
  first_name: string | null
  last_name: string | null
  gender: string | null
  biography: string | null
  tags: string[]
  images: {
    file: File
    url: string
  }[]
}
