<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCartRequest;
use App\Models\CartItem;
use App\Models\ProductSku;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(AddCartRequest $request)
    {
        $user = $request->user();
        $skuId = $request->input('sku_id');
        $amount = $request->input('amount');

        //查询该产品是否已经再购物车中
        if($cart = $user->cartItems()->where('product_sku_id', $skuId)->first()){
            //如果存在则直接叠加产品数量
            $cart->update([
                'amount' => $cart->amount + $amount
            ]);
        }else{
            //创建一个新的购物车记录
            $cart = new CartItem(['amount' =>$amount]);
            $cart->user()->associate($user);
            $cart->productSku()->associate($skuId);
            $cart->save();
        }

        return [];
    }

    public function index(Request $request)
    {
        //with()预加载防止N+1查询问题 .的方式支持加载多层级关联
        $cartItems = $request->user()->cartItems()->with(['productSku.product'])->get();
        //获取收货地址
        $addresses = $request->user()->addresses()->orderBy('last_used_at', 'desc')->get();

        return view('cart.index', ['cartItems' => $cartItems, 'addresses' => $addresses]);
    }

    public function remove(ProductSku $sku, Request $request)
    {
        $request->user()->cartItems()->where('product_sku_id', $sku->id)->delete();
        return [];
    }
}
