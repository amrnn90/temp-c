<?php

namespace App\Admin\Fields;

use Illuminate\Support\Arr;

class Image extends Field
{
  public function __construct(...$args)
  {
    parent::__construct(...$args);

    $this->addOption('upload_url', route('admin.api.upload', [$this->resource->name(), $this->name()]));
  }

  public function multiple()
  {
    $this->addOption('multiple', true);
    return $this;
  }

  public function handleCreate($model, $request)
  {
    if (!$this->checkCanSet($model)) return;

    $name = $this->name();

    

    $model->$name = Arr::only($request->get($name), ['path', 'disk', 'meta']);
  }

  public function handleUpdate($model, $request)
  {
    if (!$this->checkCanSet($model)) return;

    $name = $this->name();

    $model->$name = Arr::only($request->get($name), ['path', 'disk', 'meta']);
  }
}
