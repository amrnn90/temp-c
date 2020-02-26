<?php

namespace App\Admin\Fields;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class Image extends Field
{
  protected function defaultOptions()
  {
    return [
      'multiple' => false,
    ];
  }

  public function init($resource, $parentField = null)
  {
    parent::init($resource, $parentField);
    $this->addOption('upload_url', route('admin.api.upload', [$this->resource->name(), $this->nestedName()]));
  }

  public function multiple()
  {
    $this->addOption('multiple', true);
    return $this;
  }

  public function handleCreate($model, $value)
  {
    if (!$this->checkCanSet($model)) return;

    $name = $this->name();

    $files = collect($value) ?? [];

    $files = $files->map(function ($file) {
      return Arr::only($file, ['path', 'disk', 'meta']);
    });

    if (!$this->options['multiple']) {
      $model->{$name} = $files[0] ?? [];
      return;
    }

    $model->{$name} =  $files;
  }

  public function handleUpdate($model, $value)
  {
    if (!$this->checkCanSet($model)) return;

    $name = $this->name();

    $files = collect($value) ?? [];


    $files = $files->map(function ($file) {
      return Arr::only($file, ['path', 'disk', 'meta']);
    });

    if (!$this->options['multiple']) {
      $model->{$name} = $files[0] ?? [];
      return;
    }

    $model->{$name} =  $files;
  }

  public function getDataFromSlice($currentSlice)
  {
    $files = parent::getDataFromSlice($currentSlice);

    if (!$files) {
      return [];
    }

    $files = is_string($files) ? json_decode($files, true): json_decode(json_encode($files), true);


    if (!isset($files[0])) {
      $files = [$files];
    }

    return collect($files ?? [])->map(function ($fileData) {
      return $fileData + ['preview' => Storage::disk($fileData['disk'])->url($fileData['path'])];
    });
  }
}
