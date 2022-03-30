<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\TotalArchive;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardsController extends Controller
{

    public function getCountTotal(Request $request){
        $categories = Category::all()->count();
        $products = Product::all()->count();
        $users = User::all()->count();

        return json_encode(['success' => 1 , 'data' => [
            'totalCategories' => $categories,
            'totalProducts' => $products,
            'totalUsers' => $users
        ]]);
    }

    public function calculateTotals(){

        $categories = Category::all()->count();
        $products = Product::all()->count();
        $users = User::all()->count();

        TotalArchive::create([
           'total_categories' => $categories,
           'total_products' => $products,
            'total_users' => $users,
            'issue_date' => date('Y-m-d')
        ]);

        return;
    }
}
