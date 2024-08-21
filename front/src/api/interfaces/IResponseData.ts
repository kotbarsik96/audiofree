export default interface IResponseData {
  payload: Record<string | number, any>
  message?: string
  error?: string
  errors?: Record<string, string[]>
}
