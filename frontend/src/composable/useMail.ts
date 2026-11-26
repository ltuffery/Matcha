import { ref } from 'vue'
import { mailApi, type VerifyMailResponse } from '@/api/email'

export function useMail() {
  const error = ref<string | null>(null)

  const verify = async (email: string): Promise<VerifyMailResponse> => {
    const res = await mailApi.mailVerify({
      email: email,
    })

    if (!res.success) {
      error.value = res.error as string
    }
    return res
  }

  return {
    verify,

    error,
  }
}
