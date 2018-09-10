<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name='viewport' content='width=device-width, user-scalable=no'/>

  <link rel="dns-prefetch" href='//t.roomp.co'>
  <link rel="dns-prefetch" href='//i.roomp.co'>
  <link rel="shortcut icon" href="/favicon.png">
  <meta name="keywords" content="@yield('keywords')" />
  <meta name="description" content="@yield('description')" />
  <title>@yield('title')</title>

  <script>
    window.Laravel = {
      csrfToken: '{{ csrf_token() }}'
    }
  </script>

  @section('js-public')
  <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
      })(window,document,'script','dataLayer','GTM-TRHGT7L');</script>
    <!-- End Google Tag Manager -->
  @show

  @section('jstop')
    <script>
      window.translations = {!! json_encode($translations) !!}
    </script>

    @include('analytics')
  @show

  @section('css')
    @parent
    <link rel="stylesheet" href="{{ cdn('/css/fonts.css') }}"/>
  @show
</head>
<body>

@section('js-public-body')
  <!-- Google Tag Manager (noscript) -->
  <noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TRHGT7L"
                    height="0" width="0" style="display:none;visibility:hidden"></iframe>
  </noscript>
  <!-- End Google Tag Manager (noscript) -->
@show

@section('content')
@show

@section('js')
@show

</body>
</html>