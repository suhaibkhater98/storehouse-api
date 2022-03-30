<?php

namespace App\Http\Controllers\v1;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Http\Resources\v1\CategoryResource;
use App\Http\Resources\v1\CategoryCollection;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return CategoryCollection
     */
    public function index(Request $request)
    {
        if($request->withPagination)
            return new CategoryCollection(Category::paginate());
        else
            return new CategoryCollection(Category::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->all());
        return new CategoryResource($category);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCategoryRequest  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->all());
        return new CategoryResource($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $products = DB::table('products_categories')
            ->where('category_id', '=', $category->id)
            ->select('id')->get()->toArray();
        if(count($products) > 0)
            return response(['success' => 0 , 'message' => 'Ops This Category have products'] , \Symfony\Component\HttpFoundation\Response::HTTP_OK);

        if($category->delete())
            return response(['success' => 1 , 'message' => 'Deleted Successfully'], \Symfony\Component\HttpFoundation\Response::HTTP_OK);

        return response('Something went Wrong' , \Symfony\Component\HttpFoundation\Response::HTTP_NOT_ACCEPTABLE);
    }

}
