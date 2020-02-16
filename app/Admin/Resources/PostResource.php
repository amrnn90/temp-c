<?php

namespace App\Admin\Resources;

use App\Admin\Fields\ShortText;
use App\Post;
use Str;

class PostResource
{
    use ResourceAuthorization;

    protected $model = Post::class;

    protected $titleField = 'title';

    public static function make()
    {
        return new static();
    }

    public function structure()
    {
        return [
            'name' => $this->name(),
            'label' => $this->label(),
            'path' => $this->path(),
            'api_urls' => $this->apiUrls(),
            'title_field' => $this->titleField,
            'abilities' => $this->abilities(),
            'fields' => collect($this->fields())->map->structure()->all(),
        ];
    }

    public function name()
    {
        return Str::snake(class_basename(self::class));
    }

    public function label()
    {
        return Str::plural(Str::title(class_basename($this->model)));
    }

    public function getField($name)
    {
        return collect($this->fields())->filter(function ($field) use ($name) {
            return $field->name() == $name;
        })->first();
    }

    public function index()
    {
        $result = $this->scope($this->model()->query())->paginate();

        $items = collect($result->items());

        $items =  $items->map(function ($item) {
            return [
                'abilities' => $this->abilitiesForModel($item),
                'api_urls' => $this->modelApiUrls($item),
                'id' => $this->getModelId($item),
                'fields' => collect($this->fields())->map(function ($field) use ($item) {
                    return $field->structureForModel($item);
                })
            ];
        });

        $result->setCollection(collect($items));

        return $result;
    }

    public function find($id)
    {
        return $this->model()->find($id);
    }

    public function findOrFail($id)
    {
        return $this->model()->findOrFail($id);
    }

    public function destroy($model)
    {
        return $model->delete();
    }

    public function validateCreate($request)
    {
        $rules = [];
        collect($this->fields())->each(function ($field) use (&$rules) {
            $rules = $rules + $field->getCreateRules();
        });

        return $request->validate($rules);
    }

    public function store($request)
    {
        $model = $this->model();

        collect($this->fields())->each(function ($field) use ($request, $model) {
            $field->handleCreate($model, $request);
        });

        $model->save();

        return $model;
    }

    public function show($model)
    {
        return [
            'abilities' => $this->abilitiesForModel($model),
            'api_urls' => $this->modelApiUrls($model),
            'id' => $this->getModelId($model),
            'fields' => collect($this->fields())->map(function ($field) use ($model) {
                return $field->structureForModel($model);
            })
        ];
    }

    protected function fields()
    {
        return [
            ShortText::make('title')
                ->rules('required')
                ->canView(function ($user, $post) {
                    return $user->name == 'amr';
                }),
                
            ShortText::make('body')
                ->rules('required|min:10')

        ];
    }

    protected function path()
    {
        return Str::snake(Str::pluralStudly(class_basename($this->model)));
    }

    protected function apiUrls()
    {
        return [
            'index' => route('admin.api.index', $this->name()),
            'store' => route('admin.api.store', $this->name()),
        ];
    }

    protected function modelApiUrls($model)
    {
        return [
            'delete' => route('admin.api.destroy', [$this->name(), $model])
        ];
    }

    protected function model()
    {
        return (new $this->model);
    }

    protected function scope($query)
    {
        return $query;
    }

    protected function getModelId($model)
    {
        return $model->getAttribute($model->getKeyName());
    }
}
