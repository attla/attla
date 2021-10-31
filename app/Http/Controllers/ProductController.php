<?php

namespace App\Http\Controllers;

use Attla\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function list()
    {
        // $products = Product::all();
        $products = Product::orderBy('id')->paginate(10, ['id', 'name', 'price']);
        // dumper($products->appends(request()->except('page'))->links());

        return view('products.list', compact('products'));
    }

    public function store()
    {
        // $this->validate($request, [
        //     'email' => 'required|email'
        // ]);
        return view('admin');
    }
}
