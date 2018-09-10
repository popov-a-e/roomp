@extends('layouts.main')

@section('keywords',__('meta.keywords.index'))
@section('description',__('meta.description.index'))
@section('title',__('meta.title.index'))

@if (session('agent.mobile'))
  @section('content')
    @include('errors.404.mobile.content')
  @endsection

  @include('errors.404.mobile.assets')

@else
  @section('content')
    @include('errors.404.desktop.content')
  @endsection

  @include('errors.404.desktop.assets')

@endif