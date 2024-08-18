export default interface IRequestParams {
  path: string
  config?: Record<string, any>
  errorHandling?: "notification" | "toObject"
}