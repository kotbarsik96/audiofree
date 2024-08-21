export default interface IResetPasswordCheckRequest {
  email: string
  code: string
  password: string
  password_confirmation: string
}