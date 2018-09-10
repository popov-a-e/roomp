@extends('layouts.main')

@section('keywords',__('meta.keywords.index'))
@section('description',__('meta.description.index'))
@section('title',__('meta.title.index'))
@translations(header,home,footer)

@if (session('agent.mobile'))
  @section('content')
    @include('website.home.mobile.content')
  @endsection

  @include('website.home.mobile.assets')

@else
  @section('content')
    @include('website.home.content')
  @endsection

  @include('website.home.assets')

@endif

@section('og')
  <meta property="og:type" content="company">
  <meta property="og:title" content="{{  __('meta.title.index') }}">
  <meta property="og:image" content="{{ url('/img/main.jpg') }}">
  <meta property="og:description" content="{{ __('meta.description.index') }}">
  <meta property="og:locale" content="{{ $locale }}">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:site_name" content="Roomp.co">
@endsection