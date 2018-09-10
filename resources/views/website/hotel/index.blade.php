@extends('layouts.main')

@section('title', $hotel->name.' | Roomp')

@if(session('agent.mobile'))

@section('content')
@include('website.hotel.mobile.content')
@endsection
@include('website.hotel.mobile.assets')
@else

@section('content')
  @include('website.hotel.content')
@endsection
@include('website.hotel.assets')

@endif

@section('json-ld')
  <script type="application/ld+json">
    {
       "@context" : "http://schema.org",
       "name" : "{{ $hotel->name }}",
       "description" : "{{ $hotel->description  }}",
       "address" : {
          "addressCountry" : "Russia",
          "addressRegion" : "{{ $hotel->city->name }}",
          "addressLocality" : "{{ $hotel->city->name }}",
          "streetAddress" : "{{ $hotel->address }}, Russia",
          "@type" : "PostalAddress"
       },
       "hasMap" : "https://maps.googleapis.com/maps/api/staticmap?center={{$hotel->lat}},{{$hotel->lng}}&zoom=15&size=800x800&key=AIzaSyCymuQZ09lnGN7QIhQWk1CAxOz5Ie-7iLQ&markers=|{{$hotel->lat}},{{$hotel->lng}}",
       "@type" : "Hotel",
       "image" : "{{ image_url($heroimage, 2000) }}",
       "url" : "https://roomp.co/hotel/{{ $hotel->pretty_url }}"
    }
  </script>
@endsection

@section('og')
  <meta property="og:type" content="roomp_co:hotel">
  <meta property="og:title" content="{{  $hotel->name.' | Roomp' }}">
  <meta property="og:image" content="{{ image_url($heroimage, 2000) }}">
  <meta property="og:description" content="{{ $hotel->description }}">
  <meta property="og:locale" content="{{ $locale }}">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:site_name" content="Roomp.co">
@endsection