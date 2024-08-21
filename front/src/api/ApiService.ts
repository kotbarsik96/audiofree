import { apiInstance } from "@/api/Api"
import type IRequestParams from "@/api/interfaces/IRequestParams"
import type IResponseData from "@/api/interfaces/IResponseData"
import { useNotifications } from "@/composables/useNotifications"
import { LStorageKeys } from "@/enums/LStorageKeys"
import { lStorageGetItem } from "@/utils/lStorage"

class ApiService {
  api: typeof apiInstance
  notifications: ReturnType<typeof useNotifications>

  constructor() {
    this.api = apiInstance
    this.notifications = useNotifications()
    this.setTokenHeader()
  }

  getDefaultParams(params: IRequestParams): IRequestParams {
    return Object.assign(
      {
        config: {},
        errorHandling: "notification",
      },
      params
    )
  }

  getUrl(path: string, urlParams?: any) {
    let url = `${this.api.baseUrl}/${path}`
    if (urlParams) url = `${url}?${new URLSearchParams(urlParams).toString()}`
    return url
  }

  async handleResponse(
    response: Response,
    params: IRequestParams
  ): Promise<IResponseData> {
    const data = await response.json()
    let error
    let errors

    if (!response.ok) {
      // если вообще нет информации об ошибке - вывести в нотификацию стандартную
      if (!data?.error && !data?.errors && !data?.message) throw new Error()
      // если есть message, но нет ошибки или списка ошибок - вывести message в нотификацию
      if (data?.message && !data?.error && !data?.errors)
        throw new Error(data.message)

      // выведет текст в нотификацию, выбросит ошибку: данные не будут переданы по цепочке
      if (params.errorHandling === "notification") {
        error = data?.error || data?.message
        throw new Error(error)
      }
      // пробросит текст ошибки в объект, не выбросит ошибку: данные будут переданы по цепочке
      else if (params.errorHandling === "toObject") {
        error = data?.error || data?.message
        errors = data?.errors
      }
    }

    return { data, error, errors }
  }

  handleError(err: any) {
    let message = "Произошла ошибка"
    if (err.name === "Error" && err.message) message = err.message

    this.notifications.addNotification("error", message)
  }

  public setHeaders(headers: Record<string, string>) {
    this.api.setHeaders(headers)
  }

  public setTokenHeader() {
    this.api.setHeaders({
      Authorization: `Bearer ${lStorageGetItem(LStorageKeys.JWT)}`,
    })
  }

  public async GET(params: IRequestParams): Promise<IResponseData | undefined> {
    const _params = this.getDefaultParams(params)
    const urlWithParams = this.getUrl(_params.path, _params.config)
    let handledResponse

    try {
      const response = await fetch(urlWithParams, {
        method: "GET",
        headers: this.api.headers,
      })
      handledResponse = await this.handleResponse(response, _params)
    } catch (err) {
      this.handleError(err)
    }

    return handledResponse
  }
  public async POST(
    params: IRequestParams
  ): Promise<IResponseData | undefined> {
    const _params = this.getDefaultParams(params)
    const url = this.getUrl(_params.path)
    let handledResponse

    try {
      const response = await fetch(url, {
        method: "POST",
        body: JSON.stringify(_params.config),
        headers: this.api.headers,
      })
      handledResponse = await this.handleResponse(response, _params)
    } catch (err) {
      this.handleError(err)
    }

    return handledResponse
  }
  public async DELETE(
    params: IRequestParams
  ): Promise<IResponseData | undefined> {
    const _params = this.getDefaultParams(params)
    const urlWithParams = this.getUrl(_params.path, _params.config)
    let handledResponse

    try {
      const response = await fetch(urlWithParams, {
        method: "DELETE",
        headers: this.api.headers,
      })
      handledResponse = await this.handleResponse(response, _params)
    } catch (err) {
      this.handleError(err)
    }

    return handledResponse
  }
}

export const apiServiceInstance = new ApiService()
