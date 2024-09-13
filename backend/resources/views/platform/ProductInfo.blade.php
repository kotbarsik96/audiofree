<div class="p-info-table" data-controller="info-table">
  <table>
    <tbody>
      @foreach ($info as $key => $item)
      <tr data-info-table-target="row" data-i="{{ $key + 1 }}">
        <td>
          <div class="d-flex align-items-center justify-content-sm-between">
            <input type="text" name="infoName[]" value="{{ $item->name }}" data-origin-value="{{ $item->name }}" data-action="input->info-table#onCellInput">
            <button class="p-info-table__refresh-btn _icon-btn" type="button" data-action="click->info-table#refreshCell">
              <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none">
                <path
                  d="M4.06189 13C4.02104 12.6724 4 12.3387 4 12C4 7.58172 7.58172 4 12 4C14.5006 4 16.7332 5.14727 18.2002 6.94416M19.9381 11C19.979 11.3276 20 11.6613 20 12C20 16.4183 16.4183 20 12 20C9.61061 20 7.46589 18.9525 6 17.2916M9 17H6V17.2916M18.2002 4V6.94416M18.2002 6.94416V6.99993L15.2002 7M6 20V17.2916"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </button>
          </div>
        </td>
        <td>
          <div class="d-flex align-items-center justify-content-sm-between">
            <input type="text" name="infoValue[]" value="{{ $item->value }}" data-origin-value="{{ $item->value }}" data-action="input->info-table#onCellInput">
            <button class="p-info-table__refresh-btn _icon-btn" type="button" data-action="click->info-table#refreshCell">
              <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none">
                <path
                  d="M4.06189 13C4.02104 12.6724 4 12.3387 4 12C4 7.58172 7.58172 4 12 4C14.5006 4 16.7332 5.14727 18.2002 6.94416M19.9381 11C19.979 11.3276 20 11.6613 20 12C20 16.4183 16.4183 20 12 20C9.61061 20 7.46589 18.9525 6 17.2916M9 17H6V17.2916M18.2002 4V6.94416M18.2002 6.94416V6.99993L15.2002 7M6 20V17.2916"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </button>
          </div>
        </td>
        <td class="p-info-table__actions">
          <button class="_icon-btn _icon-btn--red" data-info-table-target="removeRowBtn"
            data-action="click->info-table#removeRow" data-i="{{ $key + 1 }}" type="button">
            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor"
              class="overflow-visible" viewBox="0 0 16 16" role="img" path="bs.trash3" componentname="orchid-icon">
              <path
                d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5">
              </path>
            </svg>
          </button>
        </td>
      </tr>
      @endforeach

      <tr data-info-table-target="lastRowForAdd">
        <td>
          <input type="text" placeholder="{{ __('Product info title') }}"
            data-action="keydown->info-table#onBlankKeydown" data-info-table-target="addTitle">
        </td>
        <td>
          <input type="text" placeholder="{{ __('Product info value') }}"
            data-action="keydown->info-table#onBlankKeydown" data-info-table-target="addValue">
        </td>
        <td class="p-info-table__actions">
          <button class="_icon-btn _icon-btn--green" data-info-table-target="removeRowBtn"
            data-action="click->info-table#addNewRow" type="button">
            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor"
              class="overflow-visible" viewBox="0 0 16 16" role="img"
              id="field-soxranit-9dca0f6a01e9c97e80b21a8c579418fa6980111e" path="pencil" componentname="orchid-icon">
              <path
                d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325">
              </path>
            </svg>
          </button>
        </td>
      </tr>
    </tbody>
  </table>
</div>