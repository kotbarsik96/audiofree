export default interface IUser {
  id: number
  email: string
  email_verified_at: null | Date
  name: string
  surname: null | string
  patronymic: null | string
  phone_number: null | string
  location: null | string
  street: null | string
  house: null | string
  created_at: string
  updated_at: string
}
