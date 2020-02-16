<?php

namespace App\Admin\Fields;

use Str;

class ShortText
{
    protected $name;
    protected $label;
    protected $abilities;
    protected $createRules;
    // protected $updateRules;

    public function __construct($name)
    {
        $this->name = $name;
        $this->label = Str::title($name);
        $this->createRules = [];
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

    public function structure()
    {
        return [
            'name' => $this->name(),
            'label' => $this->label,
            'type' => $this->fieldType()
        ];
    }

    public function structureForModel($model)
    {
        $abilities = $this->abilitiesForModel($model);

        return $this->structure() + [
            'abilities' => $this->abilitiesForModel($model),
            'data' => $abilities['view'] ? $model->{$this->name} : null,
        ];
    }

    public function handleCreate($model, $request)
    {
        if (!$this->checkCanSet($model)) return;

        $name = $this->name();

        $model->$name = $request->get($name);
    }

    protected function abilitiesForModel($model)
    {
        return [
            'view' => $this->checkCanView($model),
            'set' => $this->checkCanSet($model)
        ];
    }

    public function canView($predicate)
    {
        $this->abilities['view'] = $predicate;
        return $this;
    }

    public function canSet($predicate)
    {
        $this->abilities['set'] = $predicate;
        return $this;
    }

    public function rules($rules)
    {
        $this->createRules = $rules;
        return $this;
    }

    public function getCreateRules()
    {
        return [$this->name() => $this->createRules];
    }

    public function label($label)
    {
        $this->label = $label;
        return $this;
    }

    public function checkCanView($model)
    {
        return $this->abilities['view'](request()->user(), $model);
    }

    public function checkCanSet($model)
    {
        return $this->abilities['set'](request()->user(), $model);
    }

    public function name()
    {
        return $this->name;
    }

    protected function fieldType()
    {
        return class_basename($this);
    }
}
