<?php

namespace App\Models\Traits;

trait HandleOrchidAttachments
{
  public function attachSingle($attGroup, $attId = null)
  {
    if ($attId) {
      $this->detachByGroup($attGroup);
      $this->attachment()->sync($attId);
    }
  }

  public function detachByGroup($attGroup)
  {
    $this->attachment($attGroup)->detach();
  }

  public function attachMany($ids = null)
  {
    if ($ids) {
      $this->attachment()->sync($ids);
    }
  }

  public function attachManyWithDetaching($attGroup, $ids = null)
  {
    $this->detachByGroup($attGroup);
    $this->attachMany($ids);
  }

  public function detachAll()
  {
    $this->attachment()->detach();
  }
}
