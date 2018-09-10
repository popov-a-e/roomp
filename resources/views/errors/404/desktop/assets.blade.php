@section('css')
  @parent
  <link rel="stylesheet" href="{{ cdn('/css/errors/404.css') }}"/>
@endsection

@section('jstop')
  @parent
  <script>
    window.translations = {!! json_encode($translations) !!};
  </script>

  @include('analytics')
@endsection

@section('js')
  <script src="{{ cdn('/js/errors/404.js') }}"></script>
@endsection
