@extends('website.static.layout')

@section('keywords',__('meta.keywords.static.guest'))
@section('description',__('meta.description.static.guest'))
@section('title',__('meta.title.static.guest'))

@if (session('agent.mobile'))
  @include('website.static.mobile.guest')
@else
  @include('website.static.desktop.guest')
@endif