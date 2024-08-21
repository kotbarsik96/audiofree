import { apiServiceInstance } from "@/api/ApiService"
import type IResponseData from "@/api/interfaces/IResponseData"
import type ILoginRequest from "@/services/User/interfaces/request/ILoginRequest"
import type IResetPasswordCheckRequest from "@/services/User/interfaces/request/IResetPasswordCheckRequest"
import type IResetPasswordRequest from "@/services/User/interfaces/request/IResetPasswordRequest"
import type ISignupRequest from "@/services/User/interfaces/request/ISignupRequest"

class UserApi {
  api: typeof apiServiceInstance

  constructor() {
    this.api = apiServiceInstance
  }

  public setTokenHeader() {
    this.api.setTokenHeader()
  }

  public async signup(
    config: ISignupRequest
  ): Promise<IResponseData | undefined> {
    return await this.api.POST({
      path: "signup",
      errorHandling: "toObject",
      config,
    })
  }

  public async login(
    config: ILoginRequest
  ): Promise<IResponseData | undefined> {
    return await this.api.POST({
      path: "login",
      errorHandling: "toObject",
      config,
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

  public async editProfile() {}

  public async getEmailVerificationCode() {}

  public async getPasswordResetLink(config: IResetPasswordRequest) {
    return await this.api.GET({
      path: "profile/reset-password",
      config,
    })
  }

  public async checkPasswordResetLink(config: IResetPasswordCheckRequest) {
    return await this.api.POST({
      path: "profile/reset-password",
      config,
    })
  }

  public async changeEmail() {}

  public async changePassword() {}
}

export const userApiInstance = new UserApi()
