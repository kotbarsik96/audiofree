function createElement(tagName, attributes = "", innerHTML = "") {
  const el = document.createElement(tagName)
  attributes.split(" ").forEach((attr) => {
    const split = attr.split("=")
    el.setAttribute(split[0], split[1].replace(/"/g, ""))
  })
  el.innerHTML = innerHTML

  return el
}

class InfoTable extends window.Controller {
  static get targets() {
    return ["lastRowForAdd", "removeRowBtn", "row", "addTitle", "addValue"]
  }

  removeRow(event) {
    const button = event.target.hasAttribute("data-i")
      ? event.target
      : event.target.closest("button[data-i]")

    const i = button.dataset.i
    const row = this.element.querySelector(`tr[data-i="${i}"]`)
    row.remove()
  }

  onBlankKeydown(event) {
    switch (event.key) {
      case "Enter":
        this.addNewRow()
        break
    }
  }

  addNewRow() {
    const name = this.addTitleTarget.value.trim()
    const value = this.addValueTarget.value.trim()
    if (!name || !value) return

    const rows = this.rowTargets.sort((a, b) => {
      return Number(b.dataset.i) - Number(a.dataset.i)
    })
    const lastRowI = rows[0]?.dataset.i ?? 0
    const nextRowI = Number(lastRowI) + 1

    const tr = createElement(
      "tr",
      `data-info-table-target="row" data-i="${nextRowI}"`,
      `
        <td>
          <input type="text" name="infoName[]" value="${name}">
        </td>
        <td>
          <input type="text" name="infoValue[]" value="${value}">
        </td>
        <td class="p-info-table__actions">
          <button class="_icon-btn _icon-btn--red" data-info-table-target="removeRowBtn" data-action="click->info-table#removeRow"
            data-i="${nextRowI}" type="button">
            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor"
              class="overflow-visible" viewBox="0 0 16 16" role="img" path="bs.trash3" componentname="orchid-icon">
              <path
                d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5">
              </path>
            </svg>
          </button>
        </td>
      `
    )

    this.lastRowForAddTarget.before(tr)
    this.rowTargets.push(tr)

    this.addTitleTarget.value = ""
    this.addValueTarget.value = ""
  }

  refreshCell(event) {
    const input = event.target.closest("td").querySelector("input")
    input.value = input.dataset.originValue
    input.dispatchEvent(new Event("input"))
  }

  onCellInput(event) {
    const input = event.target
    if (input.value === input.dataset.originValue)
      input.closest("td").classList.remove("unsaved")
    else input.closest("td").classList.add("unsaved")
  }
}

application.register("info-table", InfoTable)
