@extends('layouts.app')
@section('title', '首页')

@section('content')
  <h1>这里是首页</h1>
  <div id="example-component"></div>
  <example-component></example-component>
@stop
@section('scriptsAfterJs')
  <script>
    $(document).ready(function (){});
  </script>
  @endsection
