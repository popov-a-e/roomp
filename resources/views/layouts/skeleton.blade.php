<!DOCTYPE html>
<html>
<head>
  @include('layouts.modules.meta')
  @include('layouts.modules.title')
  @include('layouts.modules.favicon')
  @include('layouts.modules.csrf')
  @include('layouts.modules.opengraph')
  @include('layouts.modules.client_chat')

  @include('layouts.modules.fonts')

  @section('js-public')
  @show

  @section('jstop')
  @show

  @section('json-ld')
  @show

  @section('css')
  @show
</head>
<body itemscope itemtype="http://schema.org/WebPage">
@section('content')
@show

@section('js')
@show
</body>
</html>
