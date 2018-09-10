@section('css')
  @parent
  <link rel="stylesheet" href="{{ cdn('/css/hotelier/index.css') }}"/>
@endsection

@section('jstop')
  @parent
  <script>
    window.translations = {!! json_encode($translations) !!};
  </script>
@endsection

@section('js')
  <script src="{{ cdn('/js/hotelier/registration.js') }}"></script>
@endsection
