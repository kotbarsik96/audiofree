import type ILoginRequest from "@/services/User/interfaces/request/ILoginRequest"
import type IResetPasswordLinkRequest from "@/services/User/interfaces/request/IResetPasswordLinkRequest"
import type IResetPasswordRequest from "@/services/User/interfaces/request/IResetPasswordRequest"
import type IResetPasswordVerifyRequest from "@/services/User/interfaces/request/IResetPasswordVerifyRequest"
import type ISignupRequest from "@/services/User/interfaces/request/ISignupRequest"
import type IVerifyEmailRequest from "@/services/User/interfaces/request/IVerifyEmailRequest"
import { userApiInstance } from "@/services/User/UserApi"

export default class UserService {
  api: typeof userApiInstance

  constructor() {
    this.api = userApiInstance
  }

  public async signup(config: ISignupRequest) {
    try {
      return await this.api.signup(config)
    } catch (err) {
      return null
    }
  }

  public async login(config: ILoginRequest) {
    try {
      return await this.api.login(config)
    } catch (err) {
      return null
    }
  }

  public async logout() {
    try {
      return await this.api.logout()
    } catch (err) {
      return null
    }
  }

  public async getUser() {
    try {
      return await this.api.getUser()
    } catch (err) {
      return null
    }
  }

  public async editProfile() {}

  public async sendPasswordResetLink(config: IResetPasswordLinkRequest) {
    try {
      return await this.api.sendPasswordResetLink(config)
    } catch (err) {
      return null
    }
  }
  public async verifyPasswordResetLink(config: IResetPasswordVerifyRequest) {
    try {
      return await this.api.verifyPasswordResetLink(config)
    } catch (err) {
      return null
    }
  }
  public async resetPassword(config: IResetPasswordRequest) {
    try {
      return await this.api.resetPassword(config)
    } catch (err) {
      return null
    }
  }

  public async sendEmailVerificationCode() {
    try {
      return await this.api.sendEmailVerificationCode()
    } catch (err) {
      return null
    }
  }
  public async verifyEmailVerificationLink(config: IVerifyEmailRequest) {
    try {
      return await this.api.verifyEmailVerificationLink(config)
    } catch (err) {
      return null
    }
  }

  public async changeEmail() {}

  public async changePassword() {}

  public setTokenHeader() {
    this.api.setTokenHeader()
  }
}
