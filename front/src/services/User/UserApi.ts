import { apiServiceInstance } from "@/api/ApiService"
import type IResponseData from "@/api/interfaces/IResponseData"
import type ILoginRequest from "@/services/User/interfaces/request/ILoginRequest"
import type ISignupRequest from "@/services/User/interfaces/request/ISignupRequest"

class UserApi {
  api: typeof apiServiceInstance

  constructor() {
    this.api = apiServiceInstance
  }

  public async signup(
    config: ISignupRequest
  ): Promise<IResponseData | undefined> {
    return await this.api.POST({
      path: "signup",
      errorHandling: "toObject",
      config: config,
    })
  }
  public async login(
    config: ILoginRequest
  ): Promise<IResponseData | undefined> {
    return await this.api.POST({
      path: "login",
      errorHandling: "toObject",
      config: config,
    })
  }
  public async logout(): Promise<IResponseData | undefined> {
    return await this.api.POST({
      path: "logout",
    })
  }
  public async getUser(): Promise<IResponseData | undefined> {
    return await this.api.GET({
      path: "profile/user",
    })
  }
  public setTokenHeader() {
    this.api.setTokenHeader()
  }
}

export const userApiInstance = new UserApi()
