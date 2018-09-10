@section('css')
  @parent
  <link rel="stylesheet" href="{{ cdn('/css/bd/index.css') }}"/>

@endsection

@section('jstop')
  <script>
    window.translations = {!! json_encode($translations) !!};
  </script>
@endsection

@section('js')
  <script src="{{ cdn('/js/bd/index.js') }}"></script>
@endsection
