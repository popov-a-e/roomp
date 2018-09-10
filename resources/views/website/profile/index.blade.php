@extends('layouts.main')

@section('title',__('profile.my_profile'))

@section('js-public')
@endsection
@section('js-public-body')
@endsection

@if (session('agent.mobile'))

  @section('content')
    @include('website.profile.mobile.content')
  @endsection
  @include('website.profile.mobile.assets')

@else

  @section('content')
    @include('website.profile.content')
  @endsection
  @include('website.profile.assets')

@endif