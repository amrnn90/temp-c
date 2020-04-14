<?php

namespace App\Admin\Fields;

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
      'fields' => $this->getFields()->map->structureForModel($model),
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

  public function getViewValue($model, $modelSlice)
  {
    $data = parent::getViewValue($model, $modelSlice);

    if (!$data) return $data;

    $data = is_array($data) ? $data : json_decode($data, true);

    $this->getFields()->each(function ($field) use ($model, &$data) {
      $data[$field->name()] = $field->getViewValue($model, $data[$field->name()]);
    });

    return $data;
  }

  public function getCreateValue($model, $modelSlice, $requestSlice)
  {
    return $this->getMutationValue($model, $modelSlice, $requestSlice, 'create');
  }


  public function getUpdateValue($model, $modelSlice, $requestSlice)
  {
    return $this->getMutationValue($model, $modelSlice, $requestSlice, 'update');
  }

  public function getMutationValue($model, $modelSlice, $requestSlice, $method)
  {
    if (!$this->checkCanSet($model)) return $modelSlice;

    $result = [];

    $this->getFields()->each(function ($field) use ($model, $modelSlice, $requestSlice,  $method, &$result) {
      $result[$field->name()] = $field->{"get{$method}Value"}($model, data_get($modelSlice, $field->name()), data_get($requestSlice, $field->name()));
    });

    return $result;
  }

  public function getCreateRules()
  {
    $rules = parent::getCreateRules();

    $this->getFields()->each(function ($field) use (&$rules) {
      $rules = array_merge($rules, $field->getCreateRules());
    });

    return $rules;
  }

  public function getUpdateRules()
  {
    $rules = parent::getUpdateRules();

    $this->getFields()->each(function ($field) use (&$rules) {
      $rules = array_merge($rules, $field->getUpdateRules());
    });

    return $rules;
  }
}
