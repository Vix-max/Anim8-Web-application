<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getAllProducts()
    {
        $products = Product::all()->map(function($product) {
            $product->buy_price = (float) $product->buy_price;
            $product->sell_price = (float) $product->sell_price;
            // Return base64 image in the response
            return $product;
        });
        return response()->json(['products' => $products]);
    }

    public function addProduct(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'product_category' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'buy_price' => 'required|numeric',
            'sell_price' => 'required|numeric',
            'product_description' => 'required|string',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $product = new Product();
        $product->product_name = $request->product_name;
        $product->product_category = $request->product_category;
        $product->quantity = $request->quantity;
        $product->buy_price = $request->buy_price;
        $product->sell_price = $request->sell_price;
        $product->product_description = $request->product_description;

        // Handle image as base64 string
        if ($request->hasFile('product_image')) {
            $image = $request->file('product_image');
            $imageData = base64_encode(file_get_contents($image->getRealPath()));
            $product->product_image = $imageData;
        }

        $product->save();

        return response()->json(['message' => 'Product added successfully'], 201);
    }

    // Add this method in ProductController.php
        public function getProductById($id)
        {
            // Use findOrFail for a cleaner way to handle product not found cases
            $product = Product::find($id);
        
            if (!$product) {
                return response()->json(['message' => 'Product not found'], 404);
            }
        
            // Cast prices to float for consistency
            $product->buy_price = (float) $product->buy_price;
            $product->sell_price = (float) $product->sell_price;
        
            // Return the product as JSON
            return response()->json(['product' => $product], 200);
        }

        public function getProductByName($name)
        {
            // Use where to find the product by product_name
            $product = Product::where('product_name', $name)->first();
            
            if (!$product) {
                return response()->json(['message' => 'Product not found'], 404);
            }
            
            // Cast prices to float for consistency
            $product->buy_price = (float) $product->buy_price;
            $product->sell_price = (float) $product->sell_price;
            
            // Return the product as JSON
            return response()->json(['product' => $product], 200);
        }


    

}

