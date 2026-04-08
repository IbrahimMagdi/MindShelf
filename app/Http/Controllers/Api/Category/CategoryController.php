<?php

namespace App\Http\Controllers\Api\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Category\CategoryRequest;
use App\Services\Categories\CategoryService;
use App\Http\Resources\Category\CategoryResource;
use App\Helpers\ApiResponse;

class CategoryController extends Controller
{
    public function __construct(protected CategoryRequest $request, protected CategoryService $service) {}

    public function index()
    {}

}
