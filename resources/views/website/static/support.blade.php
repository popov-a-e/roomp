@extends('website.static.layout')


@section('keywords',__('meta.keywords.static.support'))
@section('description',__('meta.description.static.support'))
@section('title',__('meta.title.static.support'))

@if (session('agent.mobile'))
  @include('website.static.mobile.support')
@else
  @include('website.static.desktop.support')
@endif