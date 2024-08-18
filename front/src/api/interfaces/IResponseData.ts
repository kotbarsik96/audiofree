export default interface IResponseData {
  data: Record<string | number, any>
  error?: string
  errors?: Record<string, string[]>
}
