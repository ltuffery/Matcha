import { ApiClient } from '@/api/client'

export interface VerifyMailData {
  email: string
}

export interface VerifyMailResponse {
  success: boolean
  error?: string
}

export const mailApi = {
  async mailVerify(data: VerifyMailData) {
    return ApiClient.post<VerifyMailResponse>('/email/verif/', data).then(
      res => res.json(),
    )
  },
}
