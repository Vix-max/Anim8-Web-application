<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function addOrder(Request $request)
{
    // Validate the request
    $request->validate([
        'user_id' => 'required|integer',
        'subtotal' => 'required|numeric',
        'total' => 'required|numeric',
        'shipping' => 'required|numeric',
        'order_items' => 'required|array',
        'order_items.*.product_name' => 'required|string',
        'order_items.*.quantity' => 'required|integer',
        'order_items.*.price' => 'required|numeric',
    ]);

    // Start a database transaction
    DB::beginTransaction();

    try {
        // Create the order
        $order = Order::create([
            'user_id' => $request->user_id,
            'subtotal' => $request->subtotal,
            'total' => $request->total,
            'shipping' => $request->shipping,
        ]);

        // Create order items
        foreach ($request->order_items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_name' => $item['product_name'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        // Commit the transaction
        DB::commit();

        return response()->json(['message' => 'Order placed successfully'], 201);
    } catch (\Exception $e) {
        // Rollback the transaction on error
        DB::rollback();
        return response()->json(['message' => 'Error placing order'], 500);
    }
}

    public function getOrdersByUser($userId)
    {
        $orders = Order::where('user_id', $userId)->get();
        return response()->json($orders);
    }

    public function getOrderItems($orderId)
    {
        // Find the order by its ID
        $order = Order::find($orderId);

        if (!$order) {
            return response()->json(['message' => 'Order not found.'], 404);
        }

        // Get the order items associated with this order
        $orderItems = $order->orderItems; // This uses the relationship defined in Order model

        return response()->json($orderItems);
    }

    public function getAllOrders()
    {
        // Retrieve all orders from the database
        $orders = Order::all();
    
        // Return the orders as a JSON response in an array with a key
        return response()->json([
            'orders' => $orders
        ]);
    }
    



    


}
