<?php

namespace App\Admin\Fields;

class Boolean extends Field
{
  public function handleCreate($model, $value)
  {
    if (!$this->checkCanSet($model)) return;

    $name = $this->name();

    $model->$name = !!$value;
  }

  public function handleUpdate($model, $value)
  {
    if (!$this->checkCanSet($model)) return;

    $name = $this->name();

    $model->$name = !!$value;
  }
}
