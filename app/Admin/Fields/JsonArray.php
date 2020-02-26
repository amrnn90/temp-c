<?php

namespace App\Admin\Fields;

use stdClass;

class JsonArray extends Field
{
  protected $templateField;
  protected $templateFieldCallback;


  public function structure()
  {
    return parent::structure() + [
      'template_field' => $this->getTemplateField()->structure()
    ];
  }

  // public function structureForModel($model)
  // {
  //   return parent::structureForModel($model) + [
  //     'fields' => $this->getFields()->map(function ($field) use ($model) {
  //       return $field->structureForModel($model);
  //     }),
  //   ];
  // }

  public function templateField($callback)
  {
    $this->templateFieldCallback = $callback;
    return $this;
  }

  public function getTemplateField()
  {
    if (!!$this->templateField) return $this->templateField;

    $templateField = ($this->templateFieldCallback)();

    // $templateField->setResource($this->resource);
    // $templateField->setParentField($this);
    // $templateField->setNestedName("{$this->name()}.*");
    $templateField->init($this->resource, $this);

    $this->templateField = $templateField;

    return $this->templateField;
  }

  public function getChildNestedName($child)
  {
    return "{$this->nestedName()}.*";
  }




  // public function fields($callback)
  // {
  //   $this->fieldsCallback = $callback;
  //   return $this;
  // }

  // public function getFields()
  // {
  //   if (!!$this->fields) return $this->fields;

  //   $this->fields = collect(($this->fieldsCallback)())->map(function ($field) {
  //     $field->setResource($this->resource);
  //     $field->setParentField($this);
  //     $field->init();
  //     return $field;
  //   });

  //   return $this->fields;
  // }

  // public function walkFields($callback)
  // {
  //   $field = $this->getTemplateField();
  //   $callback($field);
  //   if (method_exists($field, 'walkFields')) {
  //     $field->walkFields($callback);
  //   }
  // }



  // /* MUST REFACTOR */


  // public function handleCreate($model, $value)
  // {
  //   if (!$this->checkCanSet($model)) return;

  //   $nestedModel = new stdClass();

  //   $this->getFields()->each(function ($field) use ($value, $nestedModel) {
  //     $field->handleCreate($nestedModel, data_get($value, $field->name()));
  //   });

  //   $model->{$this->name()} = json_encode($nestedModel);
  // }


  // public function getCreateRules()
  // {
  //   $rules = [$this->name() => $this->createRules];
  //   $nestedRules = [];
  //   $this->getFields()->each(function ($field) use (&$nestedRules) {
  //     $nestedRules = $nestedRules + $field->getCreateRules();
  //   });

  //   foreach (array_keys($nestedRules) as $key) {
  //     $rules["{$this->name()}.{$key}"] = $nestedRules[$key];
  //   }

  //   return $rules;
  // }


  // public function handleUpdate($model, $value)
  // {
  //   if (!$this->checkCanSet($model)) return;

  //   $nestedModel = new stdClass();

  //   $this->getFields()->each(function ($field) use ($value, $nestedModel) {
  //     $field->handleCreate($nestedModel, data_get($value, $field->name()));
  //   });

  //   $model->{$this->name()} = json_encode($nestedModel);
  // }

  // public function getUpdateRules()
  // {
  //   $rules = [$this->name() => $this->updateRules];
  //   $nestedRules = [];
  //   $this->getFields()->each(function ($field) use (&$nestedRules) {
  //     $nestedRules = $nestedRules + $field->getUpdateRules();
  //   });

  //   foreach (array_keys($nestedRules) as $key) {
  //     $rules["{$this->name()}.{$key}"] = $nestedRules[$key];
  //   }

  //   return $rules;
  // }


  // protected function getDataForModel($model)
  // {
  //   $data = parent::getDataForModel($model);

  //   $data = is_string($data) ? json_decode($data) : (object) $data;

  //   $this->getFields()->each(function($field) use(&$data) {
  //     $data->{$field->name()} = $field->getDataForModel($data);
  //   });

  //   return $data;
  // }
}
