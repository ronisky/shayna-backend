<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit', 6);
        $name = $request->input('name');
        $slug = $request->input('slug');
        $type = $request->input('type');
        $price_from = $request->input('price_from');
        $price_to = $request->input('price_to');

        if ($id) {
            $product = Product::with('galleries')->find($id);

            if ($product)
                return ResponseFormatter::success($product, 'Data product berhasil diambil');
            else
                return ResponseFormatter::error(null, 'Data produk tidak ada', 404);
        }

        if ($slug) {
            $product = Product::with('galleries')
                ->where('slug', $slug)
                ->first();

            if ($product)
                return ResponseFormatter::success($product, 'Data product berhasil diambil');
            else
                return ResponseFormatter::error(null, 'Data produk tidak ada', 404);
            // return $this->errorResponse(null, 'Data produk tidak ada');
        }

        $product = Product::with('galleries');

        if ($name)
            $product->where('name', 'like', '%' . $name . '%');

        if ($type)
            $product->where('type', 'like', '%' . $name . '%');

        if ($price_from)
            $product->where('price', '>=', $price_from);

        if ($price_to)
            $product->where('price', '<=', $price_to);

        return ResponseFormatter::success(
            $product->paginate($limit),
            'Data list product berhasil diambil'
        );
        // $productAll = Product::all();
        // return $this->successResponse($productAll, 'Data list product berhasil diambil');
    }
}
