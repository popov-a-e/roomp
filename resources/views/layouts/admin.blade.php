<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name='viewport' content='width=device-width, user-scalable=no'/>

  <link rel="dns-prefetch" href='//roomp.co'>
  <link rel="dns-prefetch" href='//admin.roomp.co'>
  <link rel="shortcut icon" href="/favicon.png">
  <title>@yield('title')</title>

  <script>
    window.Laravel = {
      csrfToken: '{{ csrf_token() }}'
    };
    window.cloud_storage_root = '{{ cloud_storage_root() }}';
  </script>
  @section('jstop')
  @show

  @section('css')
    <link rel="stylesheet" href="{{ cdn('/css/fonts.css') }}"/>
  @show
</head>
<body>
@section('content')
@show


@section('js')
@show

</body>
</html>
