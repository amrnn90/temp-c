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

  public function getViewValue($model, $modelSlice)
  {
    $files = parent::getViewValue($model, $modelSlice);

    if (!$files) return $files;

    $files = is_array($files) ? $files : json_decode($files, true);


    if (!isset($files[0])) {
      $files = [$files];
    }

    return collect($files ?? [])->map(function ($fileData) {
      return $fileData + ['preview' => Storage::disk($fileData['disk'])->url($fileData['path'])];
    });
  }

  public function getCreateValue($model, $modelSlice, $requestSlice)
  {
    return $this->getUpdateValue($model, $modelSlice, $requestSlice);
  }

  public function getUpdateValue($model, $modelSlice, $requestSlice)
  {
    if (!$this->checkCanSet($model)) return $modelSlice;

    $files = collect($requestSlice) ?? [];

    $files = $files->map(function ($file) {
      return Arr::only($file, ['path', 'disk', 'meta']);
    });

    return $this->options['multiple'] ? $files : ($files[0] ?? []);
  }
}
