<?php

namespace App\Admin\Fields;

class Boolean extends Field
{
  protected function setDataToSlice($data, $currentSlice)
  {
    $currentSlice->{$this->name()} = !!$data;
  }
}
