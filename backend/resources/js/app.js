function initByElname(elName, ClassName, isSingle = false) {
  const selector = `[data-elname='${elName}']`

  if (isSingle) {
    const el = document.querySelector(selector)
    if (el) new ClassName(el)
  } else {
    document.querySelectorAll(selector).forEach((el) => new ClassName(el))
  }
}

class SomeElement {
  paramsAttribute = "data-params"

  constructor(el) {
    this.el = el
    this.getParams()
  }
  getParams() {
    this.params = JSON.parse(this.el.getAttribute(this.paramsAttribute)) || {}
    this.el.removeAttribute(this.paramsAttribute)
  }
  bindContextToFunctions(functionsNames = []) {
    functionsNames.forEach((name) => {
      if (typeof this[name] === "function") this[name] = this[name].bind(this)
    })
  }
  getChild(name) {
    const selector = `[data-elchild="${name}"]`
    return this.el.querySelector(selector)
  }
  mapChildren(children = []) {
    children.forEach((dataOrName) => {
      if (typeof dataOrName === "string") {
        this[`${dataOrName}El`] = this.getChild(dataOrName)
      } else {
        const el = this.getChild(dataOrName.name)
        this[`${dataOrName.name}El`] = el
        dataOrName.callbacks?.forEach((cbItem) => {
          const target = cbItem.target || el
          const cbData = Object.entries(cbItem)[0]
          target.addEventListener(cbData[0], cbData[1])
        })
      }
    })
  }
}

class Gallery extends SomeElement {
  constructor(el) {
    super(el)

    this.bindContextToFunctions(["onUpload"])
    this.mapChildren([
      "galleryHidden",
      {
        name: "fileInput",
        callbacks: [{ change: this.onUpload }],
      },
    ])
  }
  onUpload() {}
}

// init
const els = [
  {
    name: "gallery",
    className: Gallery,
    single: false,
  },
]
els.forEach((item) => initByElname(item.name, item.className, item.single))
