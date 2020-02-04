<?php

namespace App;

use App\Admin\Resources\PostResource;

class Admin
{
    public function structure()
    {
        return [
            'resources' => collect($this->resources())->map->structure()->all(),
            'base_path' => '/admin',
            'base_api_path' => '/admin/api'
        ] ;
    }

    public function resources()
    {
        return [
            PostResource::make(),
        ];
    }

    public function getResource($name)
    {
        return collect($this->resources())->filter(function($resource) use ($name) {
            return $resource->name() == $name;
        })->first();
    }
}