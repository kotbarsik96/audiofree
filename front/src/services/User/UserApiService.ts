import { apiServiceInstance } from "@/api/ApiService"
import type IResponseData from "@/api/interfaces/IResponseData"
import type ILoginRequest from "@/services/User/interfaces/request/ILoginRequest"
import type IResetPasswordRequest from "@/services/User/interfaces/request/IResetPasswordLinkRequest"
import type IResetPasswordVerifyRequest from "@/services/User/interfaces/request/IResetPasswordVerifyRequest"
import type ISignupRequest from "@/services/User/interfaces/request/ISignupRequest"
import type IResetPasswordLinkRequest from "@/services/User/interfaces/request/IResetPasswordLinkRequest"
import type IVerifyEmailRequest from "@/services/User/interfaces/request/IVerifyEmailRequest"

export default class UserApiService {
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
    try {
      return await this.api.POST({
        path: "signup",
        errorHandling: "toObject",
        config,
      })
    } catch (err) {
      return
    }
  }

  public async login(
    config: ILoginRequest
  ): Promise<IResponseData | undefined> {
    try {
      return await this.api.POST({
        path: "login",
        errorHandling: "toObject",
        config,
      })
    } catch (err) {
      return
    }
  }

  public async logout(): Promise<IResponseData | undefined> {
    try {
      return await this.api.POST({
        path: "logout",
      })
    } catch (err) {
      return
    }
  }

  public async getUser(): Promise<IResponseData | undefined> {
    try {
      return await this.api.GET({
        path: "profile/user",
      })
    } catch (err) {
      return
    }
  }

  public async editProfile() {}

  public async sendPasswordResetLink(config: IResetPasswordLinkRequest) {
    try {
      return await this.api.POST({
        path: "profile/reset-password/request",
        config,
      })
    } catch (err) {
      return
    }
  }
  public async verifyPasswordResetLink(config: IResetPasswordVerifyRequest) {
    try {
      return await this.api.POST({
        path: "profile/reset-password/verify-link",
        config,
      })
    } catch (err) {
      return
    }
  }
  public async resetPassword(config: IResetPasswordRequest) {
    try {
      return await this.api.POST({
        path: "profile/reset-password/new-password",
        config,
      })
    } catch (err) {
      return
    }
  }

  public async sendEmailVerificationCode() {
    try {
      return await this.api.POST({
        path: "profile/verify-email/request",
      })
    } catch (err) {
      return
    }
  }
  public async verifyEmailVerificationLink(config: IVerifyEmailRequest) {
    try {
      return await this.api.POST({
        path: "profile/verify-email",
        config,
      })
    } catch (err) {
      return
    }
  }

  public async changeEmail() {}

  public async changePassword() {}
}
