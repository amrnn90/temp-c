<?php

namespace App\Admin\Fields;

class Boolean extends Field
{
  public function getCreateValue($model, $modelSlice, $requestSlice)
  {
    return $this->getUpdateValue($model, $modelSlice, $requestSlice);
  }

  public function getUpdateValue($model, $modelSlice, $requestSlice)
  {
    return !!parent::getUpdateValue($model, $modelSlice, $requestSlice);
  }
}
