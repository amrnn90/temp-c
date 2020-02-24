<?php

namespace App\Admin\Fields;

use Str;

abstract class Field
{
  protected $resource;
  protected $parentField;
  protected $name;
  protected $label;
  protected $abilities;
  protected $createRules;
  protected $updateRules;
  protected $options;

  public function __construct($name)
  {
    $this->name = $name;
    $this->label = Str::title($name);
    $this->options = $this->defaultOptions();
    $this->createRules = [];
    $this->updateRules = [];
    $this->abilities = [
      'view' => function ($user, $model) {
        return true;
      },
      'set' => function ($user, $model) {
        return true;
      }
    ];
  }

  public static function make($name)
  {
    return new static($name);
  }

  public function init()
  {
    return $this;
  }

  public function setResource($resource)
  {
    $this->resource = $resource;
    return $this; 
  }

  public function setParentField($parentField)
  {
    $this->parentField = $parentField;
    return $this;
  }

  public function name()
  {
    return $this->name;
  }

  public function nestedName()
  {
    return $this->parentField ? "{$this->parentField->nestedName()}.{$this->name()}" : $this->name();
  }

  public function label($label)
  {
    $this->label = $label;
    return $this;
  }

  public function canView($predicate)
  {
    $this->abilities['view'] = $predicate;
    return $this;
  }

  public function checkCanView($model)
  {
    return $this->abilities['view'](request()->user(), $model);
  }


  public function canSet($predicate)
  {
    $this->abilities['set'] = $predicate;
    return $this;
  }

  public function checkCanSet($model)
  {
    return $this->abilities['set'](request()->user(), $model);
  }

  public function rules($rules)
  {
    $this->createRules = $rules;
    if (!$this->updateRules) {
      $this->updateRules = $rules;
    }
    return $this;
  }

  public function getCreateRules()
  {
    return [$this->name() => $this->createRules];
  }


  public function updateRules($rules)
  {
    $this->updateRules = $rules;
    return $this;
  }

  public function getUpdateRules()
  {
    return [$this->name() => $this->updateRules];
  }

  public function handleCreate($model, $value)
  {
    if (!$this->checkCanSet($model)) return;

    $model->{$this->name()} = $value;
  }

  public function handleUpdate($model, $value)
  {
    if (!$this->checkCanSet($model)) return;

    $model->{$this->name()} = $value;
  }

  public function structure()
  {
    return [
      'name' => $this->name(),
      'nested_name' => $this->nestedName(),
      'label' => $this->label,
      'type' => $this->fieldType(),
      'options' => $this->options(),
    ];
  }

  public function structureForModel($model)
  {
    $abilities = $this->abilitiesForModel($model);

    return $this->structure() + [
      'abilities' => $this->abilitiesForModel($model),
      'data' => $abilities['view'] ? $this->getDataForModel($model) : null,
    ];
  }

  protected function getDataForModel($model)
  {
    return $model->{$this->name} ?? null;
  }

  protected function abilitiesForModel($model)
  {
    return [
      'view' => $this->checkCanView($model),
      'set' => $this->checkCanSet($model)
    ];
  }

  protected function fieldType()
  {
    return class_basename($this);
  }

  protected function addOption($name, $value)
  {
    $this->options[$name] = $value;
  }

  protected function options()
  {
    return $this->options;
  }

  protected function defaultOptions() 
  {
    return [];
  }
}
