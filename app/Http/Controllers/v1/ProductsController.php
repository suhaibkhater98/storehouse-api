<?php

namespace App\Http\Controllers\v1;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Http\Resources\v1\ProductResource;
use App\Http\Resources\v1\ProductCollection;
use App\Models\ProductsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Fileupload;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return ProductCollection
     */
    public function index(Request $request)
    {
        if(isset($request->withOutPagination) && $request->withOutPagination) {
            $products = Product::select('created_at', 'id')->get();

            $collection = collect($products);

            $collection = $collection->map(function ($item) {
                return [ 'date' => date('Y-m-d' , strtotime($item['created_at'])) , 'id' => $item['id']];
            });

            $count = $collection->groupBy('date')->map->count();

            return json_encode(['success' => 1 , 'data' => $count]);
        }

        return new ProductCollection(Product::orderBy('updated_at' , 'desc')->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductRequest  $request
     * @return ProductResource
     */
    public function store(StoreProductRequest $request)
    {
        $product = Product::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price ?? 1,
            'quantity' => $request->quantity ?? 1
        ]);
        if($product && !empty($request->categories)){
            $data = [];
            $category = explode("," , $request->categories);
            foreach ($category as $id){
                $data[] = [
                    'category_id' => $id,
                    'product_id' => $product->id
                ];
            }
            ProductsCategory::insert($data);
        }
        if($product && !empty($request->image)){
            $name = $product->id . '.'. $request->file('image')->extension();
            $request->file('image')->storeAs('public/images' , $name);
            Product::where('id',$product->id)->update(['image' => $name]);
        }
        return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return ProductResource|false|string
     */
    public function show(Product $product)
    {
        if($product){
            //$category_ids = collect(DB::table("products_categories")->where('product_id','=',$product->id)->select('category_id' , '')->get())->pluck('category_id');
            $product = $product->with(['categories'])->where('id' , '=',$product->id)->first();
            //return $product;
            $multiplied = [];
            foreach ($product->categories as $value){
                $multiplied[] = [
                    'value' => $value['id'],
                    'label' => $value['name']
                ];
            }
            if($product->image != null){
                $image =  asset('storage/images/'.$product->image);
            }
            return json_encode([
                'success' => 1,
                'data' => [
                    "id" => $product->id,
                    "name" => $product->name,
                    'description' => $product->description,
                    'price' => $product->price,
                    'quantity' => $product->quantity,
                    'created_at' => date('Y-m-d h:i:s' , strtotime($product->created_at)),
                    'category_ids' => $multiplied,
                    'image' => $image ?? null
                ]
            ]);
        }

        return json_encode(['success' => 0 , 'message' => 'Ops There are no data']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductRequest  $request
     * @param  \App\Models\Product  $product
     * @return ProductResource
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'quantity' => $request->quantity
        ]);
        ProductsCategory::where('product_id' , $product->id)->delete();
        if(!empty($request->categories)){
            $data = [];
            foreach ($request->categories as $value){
                $data[] = [
                    'category_id' => $value['value'],
                    'product_id' => $product->id,
                    'created_at' => date('Y-m-d h:i:s'),
                    'updated_at' => date('Y-m-d h:i:s'),
                ];
            }
            ProductsCategory::insert($data);
        }

        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if($product->delete())
            return response(['success' => 1,'message' => 'Deleted Successfully'], 200);
        else
            return response(['success' => 0,'message' => 'Deleted Successfully']);
    }

    /**
     * @param Product $product
     * @return \Illuminate\Http\Response
     */
    public function decQuantity(Request $request){
        $product = Product::find($request->id);
        if($product->quantity > 0){
            if($product->update(['quantity' => intval($product->quantity - 1)])){
                return response(['success' => 1 , 'message' => 'The product has been decreased successfully'] , 200);
            } else {
                return response(['success' => 0 , 'message' => 'The product has not deleted'] , 200);
            }
        } else {
            return response(['success' => 0 , 'message' => 'The product has no Quantity to decrease'] , 200);
        }
    }
}
