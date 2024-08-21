export default interface IResponseData {
  data: Record<string | number, any>
  message?: string
  error?: string
  errors?: Record<string, string[]>
}
