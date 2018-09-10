@extends('layouts.main')

@section('title', __('reservation.reservation').' '.$reservation->code)

@section('js-public')
@endsection

@section('js-public-body')
@endsection

@section('content')
  @include('extranet.reservation.content')
@endsection

@include('extranet.reservation.assets')