<?php

namespace App\Admin\Fields;

class Boolean extends Field
{
  public function handleCreate($model, $request)
  {
    if (!$this->checkCanSet($model)) return;

    $name = $this->name();

    $model->$name = !!$request->get($name);
  }

  public function handleUpdate($model, $request)
  {
    if (!$this->checkCanSet($model)) return;

    $name = $this->name();

    $model->$name = !!$request->get($name);
  }
}
