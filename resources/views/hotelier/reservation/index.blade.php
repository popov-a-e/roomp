@extends('layouts.main')

@section('title', __('reservation.reservation').' '.$reservation->code)

@section('js-public')
@endsection

@section('js-public-body')
@endsection

@section('content')
  @include('hotelier.reservation.content')
@endsection

@include('hotelier.reservation.assets')