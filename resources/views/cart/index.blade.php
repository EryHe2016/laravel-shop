@extends('layouts.app')
@section('title', '购物车')

@section('content')
  <div class="row">
    <div class="col-lg-10 offset-lg-1">
      <div class="card">
        <div class="card-header">我的购物车</div>
        <div class="card-body">
          <table class="table table-striped">
            <thead>
            <tr>
              <th><input type="checkbox" id="select-all"></th>
              <th>商品信息</th>
              <th>单价</th>
              <th>数量</th>
              <th>操作</th>
            </tr>
            </thead>
            <tbody class="product_list">
            @foreach($cartItems as $item)
              <tr data-id="{{ $item->product_sku_id }}">
                <td>
                  <input type="checkbox" name="select" value="{{ $item->product_sku_id }}" {{ $item->productSku->product->on_sale ? 'checked' : 'disabled' }}>
                </td>
                <td class="product_info">
                  <div class="preview">
                    <a target="_blank" href="{{ route('products.show', $item->productSku->product->id) }}">
                      <img src="{{ $item->productSku->product->image_url }}">
                    </a>
                  </div>
                  <div @if(!$item->productSku->product->on_sale) class="not_on_sale" @endif>
                  <span class="product_title">
                    <a target="_blank" href="{{ route('products.show', $item->productSku->product->id) }}">{{ $item->productSku->product->title }}</a>
                  </span>
                  <span class="sku_title">{{ $item->productSku->title }}</span>
                  @if(!$item->productSku->product->on_sale)
                    <span class="warning">该商品已下架</span>
                  @endif
                  </div>
                </td>
                <td><span class="price">￥ {{ $item->productSku->price }}</span></td>
                <td>
                  <input type="text" class="form-control form-control-sm amount" @if(!$item->productSku->product->on_sale) disabled @endif name="amount" value="{{ $item->amount }}">
                </td>
                <td>
                  <button class="btn btn-sm btn-danger btn-remove">移除</button>
                </td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scriptsAfterJs')
  <script>
    $(document).ready(function(){
      //移除商品
      $(".btn-remove").click(function(){
        var id = $(this).closest('tr').data('id');
        swal({
          title: "确定要将该商品移除？",
          icon: "warning",
          buttons: ['取消', '确定'],
          dangerMode: true,
        })
        .then(function(willDelete){
          //用户点击 确定 按钮，willDelete的值就会是true，否则为false
          if(!willDelete){
            return;
          }
          //请求删除商品接口
          axios.delete('/cart/'+id)
          .then(function(){
            location.reload();
          });
        });
      });

      //监听 全选/取消全选 单选框变更事件
      $("#select-all").change(function(){
        //prop()方法可以知道标签中是否包含某个属性
        var checked = $(this).prop('checked');

        //只针对不带有disabled属性的候选框 所以加上:not(disabled)条件
        $('input[name=select][type=checkbox]:not([disabled])').each(function(){
          //将勾选状态设为与目标单选框一致
          $(this).prop('checked', checked);
        });
      });
    });
  </script>
@endsection
