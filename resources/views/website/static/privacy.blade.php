@extends('website.static.layout')

@section('keywords',__('meta.keywords.static.privacy'))
@section('description',__('meta.description.static.privacy'))
@section('title',__('meta.title.static.privacy'))

@if (session('agent.mobile'))
  @include('website.static.mobile.privacy')
@else
  @include('website.static.desktop.privacy')
@endif