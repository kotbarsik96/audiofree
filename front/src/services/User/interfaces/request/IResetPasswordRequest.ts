export default interface IResetPasswordRequest {
  email: string
  code: string
  password: string
  password_confirmation: string
}