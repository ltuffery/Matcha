import { photosApi } from '@/api/photos'

export function usePhotos() {
  const fetchAll = async () => {
    return await photosApi.getPhotos()
  }

  const upload = async (photo: File) => {
    const form = new FormData()

    form.append('photos[]', photo, photo.name)

    return await photosApi.uploadPhoto(form)
  }

  const remove = async (id: number) => {
    return await photosApi.deletePhoto(id)
  }

  return {
    fetchAll,
    upload,
    remove,
  }
}
