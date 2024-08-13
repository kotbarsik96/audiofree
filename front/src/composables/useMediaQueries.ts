import { reactive, onUnmounted } from "vue"

interface IDataItem {
  media: MediaQueryList
  callback: (...args: any[]) => void
}
interface IData {
  [key: number]: IDataItem
}
interface IMatch {
  [key: number]: boolean
}
interface IArg {
  media: number
  type?: "max-width" | "min-width"
}

/* если используется не внутри компонента, указать setOnUnmount = false  */
export function useMediaQueries(initial?: IArg | IArg[], setOnUnmount = true) {
  const data: IData = reactive({})
  const matches: IMatch = reactive({})

  function register(mediaValue: number, type: string = "max-width") {
    const media = window.matchMedia(`(${type}: ${mediaValue}px)`)
    data[mediaValue] = { media, callback }
    media.addEventListener("change", callback)
    callback()

    function callback() {
      matches[mediaValue] = media.matches
    }
  }
  function unregisterAll() {
    Object.values(data).forEach(obj =>
      obj.media.removeEventListener("change", obj.callback)
    )
  }

  if (initial) {
    // сразу зарегистрирует переданные запросы
    if (Array.isArray(initial)) {
      initial.forEach(arg => register(arg.media, arg.type))
    } else {
      register(initial.media, initial.type)
    }
  }

  if (setOnUnmount) onUnmounted(() => unregisterAll())

  return {
    data,
    matches,
    register,
    unregisterAll,
  }
}
