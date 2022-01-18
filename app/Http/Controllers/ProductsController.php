<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidRequestException;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * 产品列表页
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        //创建一个查询器
        $builder = Product::query()->where('on_sale', true);
        //判断是否有search参数，有就赋值给$search变量用来模糊搜索商品
        if($search = $request->input('search', '')){
            $like = '%'.$search.'%';
            //模糊搜索商品标题，商品详情，SKU标题，SKU描述
            $builder->where(function($query) use($like){
                $query->where('title', 'like', $like)
                    ->orWhere('description', 'like', $like)
                    ->orWhereHas('skus', function($query) use($like){
                        $query->where('title', 'like', $like)
                            ->orWHere('description', 'like', $like);
                    });
            });
        }

        //是否提交 order 参数 控制商品的排序规则
        if($order = $request->input('order', '')){
            //是否以_desc或者_asc结尾
            if(preg_match('/^(.+)_(desc|asc)$/', $order, $m)){
                //如果字符串的开头是这3个字符串之一，说明是一个合法的排序值
                if(in_array($m[1], ['price', 'sold_count', 'rating'])){
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

    /**
     * 产品详情页
     * @param Product $product
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws InvalidRequestException
     */
    public function show(Product $product, Request $request)
    {
        //判断商品是否已经上架，没有上架抛出异常
        if(!$product->on_sale){
            throw new InvalidRequestException('商品未上架');
        }
        $favored = false;
        //用户未登录是返回的是null，已登录时返回用户对象
        if($user = $request->user()){
            //从当前用户已经收藏的产品中搜索ID为当前商品ID的商品
            //boolval()函数用于把值转为布尔值
            $favored = boolval($user->favoriteProducts()->find($product->id));
        }

        return view('products.show', ['product' => $product, 'favored' => $favored]);
    }

    /**
     * 收藏产品
     * @param Product $product
     * @param Request $request
     * @return array
     */
    public function favor(Product $product, Request $request)
    {
        $user = $request->user();
        if($user->favoriteProducts()->find($product->id)){
            return [];
        }
        $user->favoriteProducts()->attach($product);
        return [];
    }

    /**
     * 取消收藏
     * @param Product $product
     * @param Request $request
     * @return array
     */
    public function disfavor(Product $product, Request $request)
    {
        $user = $request->user();

        $user->favoriteProducts()->detach($product);
        return [];
    }
}
