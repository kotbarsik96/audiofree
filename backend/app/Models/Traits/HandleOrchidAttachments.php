<?php

namespace App\Models\Traits;

use Illuminate\Support\Collection;

trait HandleOrchidAttachments
{
  /** 
   * Получить коллекцию прикрепленных attachment'ов по группе
   */
  public function getAttachmentsIds(string $attGroup): Collection
  {
    return $this->attachment($attGroup)->get()->pluck('id');
  }

  /**
   * Прикрепить только один attachment к указанной группе
   * Уберет ранее прикрепленный attachment'ы
   */
  public function attachSingle($attGroup, $attId = null)
  {
    if ($attId) {
      $this->detachByGroup($attGroup);
      $this->attachment()->sync($attId);
    }
  }

  /**
   * Прикрепить переданные аттачменты, открепив все ранее прикрепленные
   */
  public function attachMany(array | null $ids = null)
  {
    if ($ids) {
      $this->attachment()->sync($ids);
    }
  }
  
  /** 
   * Открепит все attachment'ы по указанной группе
   */
  public function detachByGroup($attGroup)
  {
    $this->attachment($attGroup)->detach();
  }

  /**
   * Открепит абсолютно все attachment'ы
   */
  public function detachAll()
  {
    $this->attachment()->detach();
  }
}
