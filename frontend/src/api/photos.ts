import { ApiClient } from '@/api/client'

export interface Photo {
  id: number
  name: string
}

export const photosApi = {
  async getPhotos() {
    return ApiClient.get<Photo[]>('/users/me/photos').then(res => res.json())
  },

  async uploadPhoto(fd: FormData) {
    return ApiClient.post<Photo[]>(
      '/users/me/photos',
      fd,
    ).then(res => res.json())
  },

  async deletePhoto(id: number) {
    return ApiClient.delete(`/users/me/photos/${id}`).then(res => res.json())
  }
}
