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
   * Уберет ранее прикрепленный attachment
   */
  public function attachSingle($attGroup, $attId = null)
  {
    if ($attId) {
      $this->detachByGroup($attGroup);
      $this->attachment()->sync($attId);
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
   * Прикрепить к уже существующим attachment'ам еще несколько
   */
  public function attachMany(array | null $ids = null)
  {
    if ($ids) {
      $this->attachment()->sync($ids);
    }
  }

  /** 
   * Прикрепит по данной группе только id из массива $ids 
   * Остальные будут откреплены
   */
  public function attachManyWithDetaching($attGroup, array $ids = [])
  {
    $attachedIds = $this->getAttachmentsIds($attGroup);
    $attachedIds
      ->filter(fn($id) => !in_array($id, $ids))
      ->each(fn($id) => $this->attachment()->detach($id));

    $toAttach = collect($ids)
      ->filter(fn($id) => !in_array($id, $attachedIds->toArray()))
      ->toArray();

    $this->attachment()->sync($toAttach);
  }

  /**
   * Открепит абсолютно все attachment'ы
   */
  public function detachAll()
  {
    $this->attachment()->detach();
  }
}
