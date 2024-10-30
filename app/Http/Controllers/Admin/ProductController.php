<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Size;
use App\Models\Category;

class ProductController extends Controller
{

    public function orderBigProducts()
    {
        $products = Product::with('category')->whereIn('product_category_id', [14,15])->orderByDESC('id')->get();
        return view('admin.products.products_big', compact('products'));
    }

    public function orderSmallProducts()
    {
        $products = Product::with('category')->whereIn('product_category_id', [12,13])->orderByDESC('id')->get();
        return view('admin.products.products_small', compact('products'));
    }

    public function create(Request $request)
    {

        $type =$request->type; 
        $sizes = Size::all();
        $categories = Category::all();
        return view('admin.products.create', compact('sizes', 'categories', 'type')); 
    }

    public function store(Request $request)
    {
        if($request->category_id == 14 || $request->category_id == 15) {
            Product::create([
                "product_category_id"   => $request->category_id,
                "title"                 => $request->size,
                "premium_standard_cost" => $request->premium_standard_cost,
                "studio_lpm_total"      => $request->studio_lpm_total,
                "media_lpm_total"       => $request->media_lpm_total,
                "studio_frame_total"    => $request->studio_frame_total,
                "media_frame_total"     => $request->media_frame_total
            ]);
        } else {
            Product::create([
                "product_category_id"   => $request->category_id,
                "premium_standard_cost" => $request->premium_standard_cost,
                "qty"                   => $request->qty,
            ]);
        }

        if($request->category_id == 11 || $request->category_id == 12) {
            return redirect('admin/products/order-samll')->with('success', "Product(Small) added");
        } else {
            return redirect('admin/products/order-big')->with('success', "Product(Big) added");
        }
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
        if($request->category_id == 14 || $request->category_id == 15) {
            $product->title                 = $request->size;
            $product->studio_lpm_total      = $request->studio_lpm_total;
            $product->media_lpm_total       = $request->media_lpm_total;
            $product->studio_frame_total    = $request->studio_frame_total;
            $product->media_frame_total     = $request->media_frame_total;
        } else {
            $product->qty   = $request->qty;
        }
        $product->product_category_id   = $request->category_id;
        $product->premium_standard_cost = $request->premium_standard_cost;
        $product->save();

        if($request->category_id == 11 || $request->category_id == 12) {
            return redirect('admin/products/order-samll')->with('success', "Product(Small) updated");
        } else {
            return redirect('admin/products/order-big')->with('success', "Product(Big) updated");
        }

        // return redirect()->back()->with('success', "Product added");
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        $product->delete();
        return 1;
    }
}
