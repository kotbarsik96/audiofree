export function isMobile() {
  const regexp =
    /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i
  return !!regexp.test(navigator.userAgent)
}

export function isFirefox() {
  const browserName = navigator?.userAgent.toLowerCase()
  return browserName?.includes("firefox")
}
