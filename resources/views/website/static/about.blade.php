@extends('website.static.layout')

@section('keywords',__('meta.keywords.static.about'))
@section('description',__('meta.description.static.about'))
@section('title',__('meta.title.static.about'))


@if (session('agent.mobile'))
  @include('website.static.mobile.about')
@else
  @include('website.static.desktop.about')
@endif