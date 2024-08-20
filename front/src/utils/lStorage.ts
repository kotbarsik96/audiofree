export function lStorageGetItem(key: string) {
  return JSON.parse(localStorage.getItem(key) || "null")
}

export function lStorageSetItem(key: string, value: any) {
  localStorage.setItem(key, JSON.stringify(value))
}
