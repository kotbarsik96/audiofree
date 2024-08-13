import { isMobile, isFirefox } from "@/utils/platformChecks"

/** здесь указываются элементы, которым нужно выставлять padding-right при скрытии скролла у body */
const getScrollPaddingElems = () => {
  return [
    document.body,
    document.querySelector(".header--fixed-back .header__content"),
    document.querySelector(".header--fixed .header__content"),
  ]
}

export function setBodyScroll() {
  document.body.classList.remove("_no-scroll")

  getScrollPaddingElems().forEach((el) => {
    if (!el) return
    ;(el as HTMLElement).style.removeProperty("padding-right")
  })
}

export function hideBodyScroll() {
  const scrollbarWidth = getScrollbarWidth()
  const paddingRight = isFirefox()
    ? `${scrollbarWidth}px`
    : "var(--scrollbar-with-space)"
  if (!isMobile()) {
    getScrollPaddingElems().forEach((el) => {
      if (!el) return
      ;(el as HTMLElement).style.paddingRight = paddingRight
    })
  }
  document.body.classList.add("_no-scroll")
}

export function getScrollbarWidth() {
  const outer = document.createElement("div")
  outer.style.cssText = `
    position: absolute;
    overflow: scroll;
    z-index: -999;
    top: -100vh;
    left: -100vw;
  `
  outer.classList.add("_scroll-element")

  document.body.appendChild(outer)

  const inner = document.createElement("div")
  outer.appendChild(inner)

  const scrollbarWidth = outer.offsetWidth - inner.offsetWidth

  outer.parentNode?.removeChild(outer)

  return scrollbarWidth
}
