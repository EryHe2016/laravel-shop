<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductSku;
use App\Models\UserAddress;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function store(OrderRequest $request)
    {
        $user = $request->user();
        //开启数据库事务
        $order = \DB::transaction(function() use ($user, $request) {
            $address = UserAddress::find($request->input('address_id'));
            //更新此地址的最后使用时间
            $address->update(['last_used_at' => Carbon::now()]);
            //新建订单
            $order = new Order([
                'address'   => [    //将地址信息放入订单中
                    'address'       => $address->full_address,
                    'zip'           => $address->zip,
                    'contact_name'  => $address->contact_name,
                    'contact_phone' => $address->contact_phone,
                ],
                'remark'    => $request->input('remark'),
                'total_amount'  => 0,
            ]);
            //订单关联到当前用户
            $order->user()->associate($user);
            $order->save();

            $totalAmount = 0;
            $items = $request->input('items');
            //遍历用户提交的sku
            foreach($items as $data){
                $sku = ProductSku::find($data['sku_id']);
                //创建一个 OrderItem 并直接与当前订单关联
                $item = $order->items()->make([
                    'amount' => $data['amount'],
                    'price'  => $sku->price,
                ]);
                $item->product()->associate($sku->product_id);
                $item->productSku()->associate($sku);
                $item->save();
                $totalAmount += $sku->price * $data['amount'];
            }

            //更新订单总金额
            $order->update(['total_amount'=>$totalAmount]);

            //将下列商品从购物车中移除
            $skuIds = collect($items)->pluck('sku_id');
            $user->cartItems()->whereIn('product_sku_id', $skuIds)->delete();

            return order;
        });
    }
}
