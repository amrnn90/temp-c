<?php

namespace App\Admin\Fields;

use stdClass;

class Json extends Field
{

  protected $fields = [];
  protected $fieldsCallback = null;

  public function init($resource, $parentField = null)
  {
    parent::init($resource, $parentField);

    $this->getFields()->each(function ($field) use ($resource) {
      $field->init($resource, $this);
    });
  }

  public function structure()
  {
    return parent::structure() + [
      'fields' => $this->getFields()->map->structure()->all()
    ];
  }

  public function structureForModel($model)
  {
    return array_merge(parent::structureForModel($model), [
      'fields' => $this->getFields()->map(function ($field) use ($model) {
        return $field->structureForModel($model);
      })
    ]);
  }

  public function fields($callback)
  {
    $this->fieldsCallback = $callback;
    return $this;
  }

  public function getFields()
  {
    if (!!$this->fields) return $this->fields;

    $this->fields = collect(($this->fieldsCallback)());
    return $this->fields;
  }

  public function getChildNestedName($child)
  {
    return "{$this->nestedName()}.{$child->name()}";
  }

  /* MUST REFACTOR */


  public function getDataForModel($model, $currentSlice = null)
  {
    $data = parent::getDataForModel($model, $currentSlice = null);

    if (!$data) return $data;

    $data = is_string($data) ? json_decode($data) : (object) $data;

    $this->getFields()->each(function ($field) use ($model, &$data) {
      $data->{$field->name()} = $field->getDataForModel($model, $data);
    });

    return $data;
  }

  public function createDataForModel($data, $model, $currentSlice = null)
  {
    $this->setDataForModel('create', $data, $model, $currentSlice);

  }

  public function updateDataForModel($data, $model, $currentSlice = null)
  {
    $this->setDataForModel('update', $data, $model, $currentSlice);
  }

  public function setDataForModel($method, $data, $model, $currentSlice = null)
  {
    $currentSlice = $currentSlice ?? $model;

    if (!$this->checkCanSet($model)) return;

    $nestedSlice = $currentSlice->{$this->name()};

    $nestedSlice = is_string($nestedSlice) ? json_decode($nestedSlice) : ($nestedSlice ?? (new stdClass()) );

    $this->getFields()->each(function ($field) use ($method, $data, $model, $nestedSlice) {
      $field->{"{$method}DataForModel"}(data_get($data, $field->name()), $model, $nestedSlice);
    });

    $data = $nestedSlice;

    $this->setDataToSlice($data, $currentSlice);
  }

  public function setDataToSlice($data, $currentSlice)
  {
    $currentSlice->{$this->name()} = json_encode($data);
  }

  public function getCreateRules()
  {
    $rules = [$this->name() => $this->createRules];
    $nestedRules = [];
    $this->getFields()->each(function ($field) use (&$nestedRules) {
      $nestedRules = $nestedRules + $field->getCreateRules();
    });

    foreach (array_keys($nestedRules) as $key) {
      $rules["{$this->name()}.{$key}"] = $nestedRules[$key];
    }

    return $rules;
  }

  public function getUpdateRules()
  {
    $rules = [$this->name() => $this->updateRules];
    $nestedRules = [];
    $this->getFields()->each(function ($field) use (&$nestedRules) {
      $nestedRules = $nestedRules + $field->getUpdateRules();
    });

    foreach (array_keys($nestedRules) as $key) {
      $rules["{$this->name()}.{$key}"] = $nestedRules[$key];
    }

    return $rules;
  }
}
