@extends('layouts.main')

@section('keywords',__('meta.keywords.search'))
@section('description',__('meta.description.search'))
@section('title',__('meta.title.search'))

@if (session('agent.mobile'))

  @section('content')
    @include('website.search.mobile.content')
  @endsection

  @include('website.search.mobile.assets')

@else

  @section('content')
    @include('website.search.content')
  @endsection

  @include('website.search.assets')

@endif

@section('og')
  <meta property="og:type" content="roomp_co:search">
  <meta property="og:title" content="{{  __('meta.title.search') }}">
  <meta property="og:image" content="{{ url('/img/main.jpg') }}">
  <meta property="og:description" content="{{ __('meta.description.search') }}">
  <meta property="og:locale" content="{{ $locale }}">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:site_name" content="Roomp.co">
@endsection