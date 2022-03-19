<?php

namespace HP\CrudGenrator\Http\Controllers;

use HP\CrudGenrator\Requests\CartRequest;
use HP\CrudGenrator\Models\Cart;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::latest()->get();

        return response()->json($carts);
    }

    public function store(CartRequest $request)
    {
        $cart = Cart::create($request->all());

        return response()->json($cart, 201);
    }

    public function show($id)
    {
        $cart = Cart::findOrFail($id);

        return response()->json($cart);
    }

    public function update(CartRequest $request, $id)
    {
        $cart = Cart::findOrFail($id);
        $cart->update($request->all());

        return response()->json($cart, 200);
    }

    public function destroy($id)
    {
        Cart::destroy($id);

        return response()->json(null, 204);
    }
}
