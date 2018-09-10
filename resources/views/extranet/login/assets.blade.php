@section('css')
  @parent
  <link rel="stylesheet" href="{{ cdn('/css/extranet/login.css') }}"/>
@endsection

@section('jstop')
  @parent
  <script>
    window.translations = {!! json_encode($translations) !!};
  </script>
@endsection

@section('js')
  <script src="{{ cdn('/js/extranet/login/login.js') }}"></script>
@endsection
