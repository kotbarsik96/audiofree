class Api {
  headers: Headers
  baseUrl = import.meta.env.VITE_API_URL

  constructor() {
    this.headers = new Headers({
      Accept: "application/json, text/plain, */*",
      "Content-Type": "application/json",
    })
  }
  setHeaders(headers: Record<string, string>) {
    Object.entries(headers).forEach(([name, value]) => {
      this.headers.append(name, value)
    })
  }
}

export const apiInstance = new Api()
