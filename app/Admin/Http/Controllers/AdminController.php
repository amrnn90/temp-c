<?php

namespace App\Admin\Http\Controllers;

use App\Admin;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected $admin;

    public function __construct(Admin $admin)
    {
        $this->admin = $admin;
    }

    public function index($resourceName)
    {
        $resource = $this->resource($resourceName);

        $resource->canAccess(true);

        return $resource->index();
    }

    public function destroy($resourceName, $id)
    {
        $resource = $this->resource($resourceName);

        $model = $resource->findOrFail($id);

        $resource->canForceDelete($model, true);

        $resource->destroy($model);

        return response()->json([
            'message' => 'Deleted Successfully', 
        ], 200);
    }
    
    public function spa()
    {
        $structure = $this->admin->structure();
        return view('admin.index')->with('structure', $structure);
    }

    protected function resource($resourceName) 
    {
       return $this->admin->getResource($resourceName);
    }
}
