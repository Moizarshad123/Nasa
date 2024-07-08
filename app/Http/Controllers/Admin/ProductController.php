<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Size;
use App\Models\Category;




class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->orderByDESC('id')->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $sizes = Size::all();
        $categories = Category::all();
        return view('admin.products.create', compact('sizes', 'categories')); 
    }

    public function store(Request $request)
    {
        Product::create([
            "product_category_id"   => $request->category_id,
            "title"                 => $request->size,
            "premium_standard_cost" => $request->premium_standard_cost,
            "studio_lpm_total"      => $request->studio_lpm_total,
            "media_lpm_total"       => $request->media_lpm_total,
            "studio_frame_total"    => $request->studio_frame_total,
            "media_frame_total"     => $request->media_frame_total
        ]);

        return redirect('admin/product')->with('success', "Product added");
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $product    = Product::find($id);
        $sizes      = Size::all();
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'sizes', 'categories')); 
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        $product->product_category_id   = $request->category_id;
        $product->title                 = $request->size;
        $product->premium_standard_cost = $request->premium_standard_cost;
        $product->studio_lpm_total      = $request->studio_lpm_total;
        $product->media_lpm_total       = $request->media_lpm_total;
        $product->studio_frame_total    = $request->studio_frame_total;
        $product->media_frame_total     = $request->media_frame_total;
        $product->save();

        return redirect()->back()->with('success', "Product added");
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        $product->delete();
        return 1;
    }
}