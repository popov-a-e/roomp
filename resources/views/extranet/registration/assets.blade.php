@section('css')
  @parent
  <link rel="stylesheet" href="{{ cdn('/css/extranet/registration.css') }}"/>
@endsection

@section('jstop')
  @parent
  <script>
    window.translations = {!! json_encode($translations) !!};
  </script>
@endsection

@section('js')
  <script src="{{ cdn('/js/extranet/registration.js') }}"></script>
@endsection
