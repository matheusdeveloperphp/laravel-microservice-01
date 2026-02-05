<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateCategory;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    protected $repository;

    /**
     * @param Category $categoryModel
     */
    public function __construct(Category $categoryModel)
    {
        $this->repository = $categoryModel;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        //retornando todas as categorias com get
        $categories = $this->repository->get();
        return CategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return CategoryResource
     */
    public function store(StoreUpdateCategory $storeUpdateCategory)
    {

        $category = $this->repository->create($storeUpdateCategory->validated());
        return new CategoryResource($category);
    }

    /**
     * Display the specified resource.
     *
     * @param string $url
     * @return CategoryResource
     */
    public function show($url)
    {
        $cateory = $this->repository->where('url', $url)->firstOrFail();
        return new CategoryResource($cateory);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $url
     * @return CategoryResource
     */
    public function update(StoreUpdateCategory $storeUpdateCategory, $url)
    {
        //recupera categoria a ser editada
        $category = $this->repository->where('url', $url)->firstOrFail();

        $category->update($storeUpdateCategory->validated());

        return new CategoryResource($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $url
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($url)
    {
        $category = $this->repository->where('url', $url)->firstOrFail();

        $category->delete();

        return response()->json([], 204);

    }
}
