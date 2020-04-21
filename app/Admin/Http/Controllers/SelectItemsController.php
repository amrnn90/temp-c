<?php

namespace App\Admin\Http\Controllers;

use Illuminate\Support\Facades\Storage;


class SelectItemsController extends Controller
{
  public function index($resourceName, $fieldName)
  {
    $resource = $this->resource($resourceName);

    $field = $resource->getField($fieldName);

    $query = request('query');

    return $field->getItems($query);
  }
}
