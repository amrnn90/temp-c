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


  // do stuff

  // public function getDataForModel($model, $currentSlice = null)
  // {
  //   $data = parent::getDataForModel($model, $currentSlice = null);

  //   if (!$data) return $data;

  //   $data = is_string($data) ? json_decode($data, true) : (array) $data;

  //   for ($i=0; $i < count($data); $i++) {
  //     $data[$i] =$this->getTemplateField()->getDataForModel($model, $data[$i]);
  //   }

  //   return $data;
  // }

}
