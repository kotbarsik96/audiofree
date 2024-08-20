import type ILoginRequest from "@/services/User/interfaces/request/ILoginRequest"
import type ISignupRequest from "@/services/User/interfaces/request/ISignupRequest"
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
  public setTokenHeader() {
    this.api.setTokenHeader()
  }
}
