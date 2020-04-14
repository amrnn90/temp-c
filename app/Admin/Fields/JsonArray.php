<?php

namespace App\Admin\Fields;

use stdClass;

class JsonArray extends Field
{
  protected $templateField;
  protected $templateFieldCallback;


  public function init($resource, $parentField = null)
  {
    parent::init($resource, $parentField);

    $this->getTemplateField()->init($resource, $this);
  }

  public function structure()
  {
    return parent::structure() + [
      'template_field' => $this->getTemplateField()->structure()
    ];
  }

  public function structureForModel($model)
  {
    return array_merge(parent::structureForModel($model), [
      'template_field' => $this->getTemplateField()->structureForModel($model),
    ]);
  }


  public function templateField($callback)
  {
    $this->templateFieldCallback = $callback;
    return $this;
  }

  public function getTemplateField()
  {
    if (!!$this->templateField) return $this->templateField;

    $this->templateField =  ($this->templateFieldCallback)();

    return $this->templateField;
  }

  public function getChildNestedName($child)
  {
    return "{$this->nestedName()}.*";
  }

  public function getViewValue($model, $modelSlice)
  {
    $data = parent::getViewValue($model, $modelSlice);

    if (!$data) return $data;

    $data = is_array($data) ? $data : json_decode($data, true);

    for ($i = 0; $i < count($data); $i++) {
      $data[$i] = $this->getTemplateField()->getViewValue($model, $data[$i]);
    }

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

    $requestSlice = (array) $requestSlice ?? [];

    $result = [];

    for ($i = 0; $i < count($requestSlice); $i++) {
      $result[$i] = $this->getTemplateField()->{"get{$method}Value"}($model, data_get($modelSlice, $i), data_get($requestSlice, $i));
    }

    return $result;
  }

  public function getCreateRules()
  {
    $rules = parent::getCreateRules();

    $rules = array_merge($rules, $this->getTemplateField()->getCreateRules());

    return $rules;
  }

  public function getUpdateRules()
  {
    $rules = parent::getUpdateRules();

    $rules = array_merge($rules, $this->getTemplateField()->getUpdateRules());

    return $rules;
  }
}
