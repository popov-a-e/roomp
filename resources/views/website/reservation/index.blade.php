@extends('layouts.main')

@section('title', __('reservation.reservation').' '.$reservation->code)

@section('js-public')
@endsection

@section('js-public-body')
@endsection

@section('content')
  @include('website.reservation.content')
@endsection

@include('website.reservation.assets')