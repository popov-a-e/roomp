@extends('website.static.layout')

@section('keywords',__('meta.keywords.static.terms'))
@section('description',__('meta.description.static.terms'))
@section('title',__('meta.title.static.terms'))

@if (session('agent.mobile'))
  @include('website.static.mobile.terms')
@else
  @include('website.static.desktop.terms')
@endif