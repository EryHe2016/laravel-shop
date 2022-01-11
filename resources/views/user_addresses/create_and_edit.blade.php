@extends('layouts.app')
@section('title', '新增收货地址')

@section('content')
  <div class="row">
    <div class="col-md-10 offset-lg-1">
      <div class="card">
        <div class="card-header">
          <h2 class="text-center">
            新增收货地址
          </h2>
        </div>
        <div class="card-body">
          <!-- 输出后端报错开始 -->
          @if (count($errors) > 0)
            <div class="alert alert-danger">
              <h4>有错误发生：</h4>
              <ul>
                @foreach ($errors->all() as $error)
                  <li><i class="glyphicon glyphicon-remove"></i> {{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif
        <!-- 输出后端报错结束 -->
          <form class="form-horizontal" role="form" method="post" action="{{ route('user_addresses.store') }}">
            {{ csrf_field() }}
            <!-- inline-template 代表通过内联方式引入组件 -->
            <div class="row mb-3">
              <label for="province" class="col-md-4 col-form-label text-md-end">{{ __('省') }}</label>

              <div class="col-md-6">
                <input id="province" class="form-control" name="province" value="{{ old('province', $address->province) }}" autofocus>

                @error('province')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
            </div>
            <div class="row mb-3">
              <label for="city" class="col-md-4 col-form-label text-md-end">{{ __('市') }}</label>

              <div class="col-md-6">
                <input id="city" class="form-control" name="city" value="{{ old('city', $address->city) }}" autofocus>

                @error('city')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
            </div>
            <div class="row mb-3">
              <label for="district" class="col-md-4 col-form-label text-md-end">{{ __('区') }}</label>

              <div class="col-md-6">
                <input id="district" class="form-control" name="district" value="{{ old('district', $address->district) }}" autofocus>

                @error('district')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
            </div>
            <div class="row mb-3">
              <label for="address" class="col-md-4 col-form-label text-md-end">{{ __('详细地址') }}</label>

              <div class="col-md-6">
                <input id="address" class="form-control" name="address" value="{{ old('address', $address->address) }}" autofocus>

                @error('address')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
            </div>
            <div class="row mb-3">
              <label for="zip" class="col-md-4 col-form-label text-md-end">{{ __('邮编') }}</label>

              <div class="col-md-6">
                <input id="zip" class="form-control" name="zip" value="{{ old('zip', $address->zip)}}" autofocus>
              </div>
            </div>
            <div class="row mb-3">
              <label for="contact_name" class="col-md-4 col-form-label text-md-end">{{ __('姓名') }}</label>

              <div class="col-md-6">
                <input id="contact_name" class="form-control" name="contact_name" value="{{ old('contact_name', $address->contact_name) }}" autofocus>
              </div>
            </div>
            <div class="row mb-3">
              <label for="contact_phone" class="col-md-4 col-form-label text-md-end">{{ __('电话') }}</label>

              <div class="col-md-6">
                <input id="contact_phone" class="form-control" name="contact_phone" value="{{ old('contact_phone', $address->contact_phone) }}" autofocus>
              </div>
            </div>
              <div class="form-group row text-center">
                <div class="col-12">
                  <button type="submit" class="btn btn-primary">提交</button>
                </div>
              </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
