import { ApiClient } from '@/api/client'
import type { Genre } from '@/api/users'

export interface RegisterCredentials {
  username: string
  email: string
  password: string
  first_name: string
  last_name: string
  birthday: string
  genre: Genre
  biography: string
}

export interface RegisterErrorResponse {
  success: boolean
  error: string
}

export interface User {
  id: number
  username: string
  email: string
  birthday: string
  first_name: string
  last_name: string
  genre: Genre
  biography: string
  created_at: string
  last_connection: string
  profile_complete: boolean
}

export interface LoginCredentials {
  username: string
  password: string
}

export interface LoginResponse {
  success: boolean
  error?: string
  token?: string
  refresh?: string
}

export interface RefreshTokenData {
  refresh: string
}

export interface RefreshTokenResponse {
  success: boolean
  error?: string
  token?: string
}

export interface ForgotCredentialResponse {
  success: boolean
  error?: string
}

export const authApi = {
  async register(data: FormData): Promise<RegisterErrorResponse & User> {
    return ApiClient.post<RegisterErrorResponse & User>(
      '/auth/register',
      data,
    ).then(res => res.json())
  },
  async login(data: LoginCredentials): Promise<LoginResponse> {
    return ApiClient.post<LoginResponse>(`/auth/login`, data).then(res =>
      res.json(),
    )
  },
  async refresh(data: RefreshTokenData): Promise<RefreshTokenResponse> {
    return ApiClient.post<RefreshTokenResponse>('/auth/refresh', data).then(
      res => res.json(),
    )
  },
  async forgot(email: string) {
    return ApiClient.post<ForgotCredentialResponse>(`/forgot/credencial`).then(
      res => res.json(),
    )
  },
}
