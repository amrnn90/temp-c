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

    $files = collect($request->get($name)) ?? [];

    $files = $files->map(function ($file) {
      return Arr::only($file, ['path', 'disk', 'meta']);
    });

    if (!$this->options['multiple']) {
      $model->{$name} = $files[0] ?? [];
      return;
    }

    $model->{$name} =  $files;
  }

  public function handleUpdate($model, $request)
  {
    if (!$this->checkCanSet($model)) return;

    $name = $this->name();

    $files = collect($request->get($name)) ?? [];


    $files = $files->map(function ($file) {
      return Arr::only($file, ['path', 'disk', 'meta']);
    });

    if (!$this->options['multiple']) {
      $model->{$name} = $files[0] ?? [];
      return;
    }

    $model->{$name} =  $files;
  }

  protected function getDataForModel($model)
  {
    $files = $model->{$this->name};

    if (!$files) {
      return [];
    }

    if (!isset($files[0])) {
      $files = [$files];
    }

    return collect($files ?? [])->map(function ($fileData) {
      return $fileData + ['preview' => Storage::disk($fileData['disk'])->url($fileData['path'])];
    });
  }
}
