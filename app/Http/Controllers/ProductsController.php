<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidRequestException;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        //创建一个查询器
        $builder = Product::query()->where('on_sale', true);
        //判断是否有search参数，有就赋值给$search变量用来模糊搜索商品
        if($search = $request->input('search', '')){
            $like = '%'.$search.'%';
            //模糊搜索商品标题，商品描述，SKU标题，SKU描述
            $builder->where(function ($query) use ($like) {
                $query->where('title', 'like', $like)
                    ->orWhere('description', 'like', $like)
                    ->orWhereHas('skus', function ($query) use ($like) {
                        $query->where('title', 'like', $like)
                            ->orWhere('description', 'like', $like);
                    });
            });
        }

        //是否提交 order 参数 控制商品的排序规则
        if($order = $request->input('order', '')){
            //是否以_asc或者_desc结尾
            if(preg_match('/^(.+)_(asc|desc)$/', $order, $m)){
                //如果字符串的开头是下面3个之一，说明是一个合法的排序
                if(in_array($m[1], ['price', 'rating', 'sold_count'])){
                    //根据传入的排序值来构造排序参数
                    $builder->orderBy($m[1], $m[2]);
                }
            }
        }

        $products = $builder->paginate(16);

        return view('products.index', [
            'products' => $products,
            'filters' => [
                'search' => $search,
                'order' => $order,
            ]
        ]);
    }

    public function show(Product $product, Request $request)
    {
        //判断商品是否已经上架，没有上架抛出异常
        if(!$product->on_sale){
            throw new InvalidRequestException('商品已下架');
        }

        return view('products.show', ['product' => $product]);
    }
}
