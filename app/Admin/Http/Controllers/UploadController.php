<?php

namespace App\Admin\Http\Controllers;

use App\Admin\Models\UploadedMedia;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;



class UploadController extends Controller
{
  public function upload($resourceName, $fieldName)
  {
    $resource = $this->resource($resourceName);

    $field = $resource->getField($fieldName);

    $file = request()->file($fieldName);

    $path = Storage::disk('public')->putFile("${resourceName}/${fieldName}", $file);

    $fileData = [
      'path' => $path,
      'disk' => 'public',
      'originalName' => $file->getClientOriginalName(),
    ];

    UploadedMedia::createByFileData($fileData);

    // $fileCopy = new UploadedFile(Storage::disk($fileData['disk'])->path($fileData['path']), $fileData['originalName']);

    return [
      'value' => $fileData,
      'preview' => $this->preview($fileData)
    ];
  }

  public function preview($fileData)
  {
    return $this->fielUrl($fileData);
  }

  public function fielUrl($fileData)
  {
    return Storage::disk($fileData['disk'])->url($fileData['path']);
  }
}
