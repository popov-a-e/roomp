@extends('layouts.skeleton')

@section('jstop')
  @parent
  <script>
    window.cloud_storage_root = '{{ cloud_storage_root() }}';
  </script>
@endsection

@section('chat')
@endsection
@section('og')
@endsection