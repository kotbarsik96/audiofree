const _clickEventType = () =>
  matchMedia("(hover: none)").matches ? "touchstart" : "click"

function _clickAway(event: Event, target: Element, callback: Function): void {
  if (!target.contains(event.target as Node)) callback(event, target)
}

const EL_CALLBACK_STORE = "__click_away_callback__"

const vClickAway = {
  mounted(el: any, binding: { value: Function }) {
    // без таймаута некорректно срабатывает у попапов
    setTimeout(() => {
      el[EL_CALLBACK_STORE] = (e: Event) => _clickAway(e, el, binding.value)
      document.addEventListener(_clickEventType(), el[EL_CALLBACK_STORE])
    }, 0)
  },

  beforeUnmount(el: any) {
    setTimeout(() => {
      document.removeEventListener(_clickEventType(), el[EL_CALLBACK_STORE])
    }, 0)
  },
}

export default vClickAway
