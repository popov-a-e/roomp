<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">

  <meta name="keywords" content="@yield('keywords')"/>
  <meta name="description" content="@yield('description')"/>
  <meta name="theme-color" content="#304090">

  <title>@yield('title')</title>

  <link rel="shortcut icon" href="/favicon.png">

  <script>
    window.Laravel = {
      csrfToken: '{{ csrf_token() }}'
    };
  </script>

  @section('og')
    <meta property="og:type" content="company">
    <meta property="og:title" content="Booking.com: The largest selection of hotels, homes, and holiday rentals">
    <meta property="og:image" content="https://t-ec.bstatic.com/static/img/facebook-image-else/566c7081f1deeaca39957e96365c3908f83b95af.jpg">
    <meta property="og:description" content="Whether you’re looking for hotels, homes, or holiday rentals, you’ll always find the guaranteed lowest price. Browse our 1,534,024 accommodations in over 85,000 destinations.">
    <meta property="og:locale" content="en-gb">
    <meta property="og:url" content="https://www.booking.com/index.en-gb.html">
    <meta property="og:site_name" content="Roomp.co">
  @show

  @section('js-public')
    <script>
      (function(d, w, c) {
        w.ChatraID = '4pyANeDZkSACK8sC6';
        var s = d.createElement('script');
        w[c] = w[c] || function() {
            (w[c].q = w[c].q || []).push(arguments);
          };
        s.async = true;
        s.src = (d.location.protocol === 'https:' ? 'https:': 'http:')
          + '//call.chatra.io/chatra.js';
        if (d.head) d.head.appendChild(s);
      })(document, window, 'Chatra');
    </script>
  @show

  @section('jstop')
  @show

  @section('json-ld')
  @show

  @section('css')
    <link rel="stylesheet" href="{{ cdn('/css/fonts.css') }}"/>
  @show
</head>
<body itemscope itemtype="http://schema.org/WebPage">
@section('content')
@show

@section('js')
@show
</body>
</html>
