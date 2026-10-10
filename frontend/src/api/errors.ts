export const ApiErrorCode = {
  ProfileIncomplete: 'PROFILE_INCOMPLETE',
  Unauthenticated: 'UNAUTHENTICATED',
} as const

export type ApiErrorCode = (typeof ApiErrorCode)[keyof typeof ApiErrorCode]

export interface ApiErrorBody {
  message: string
  code?: ApiErrorCode
}

export class ApiError extends Error {
  constructor(
    public readonly status: number,
    public readonly code: ApiErrorCode | null,
    message: string,
  ) {
    super(message)
    this.name = 'ApiError'
  }

  is(code: ApiErrorCode): boolean {
    return this.code === code
  }
}
