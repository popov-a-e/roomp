@section('css')
  @parent
  <link rel="stylesheet" href="{{ cdn('/css/mobile/errors/404.css') }}"/>
@endsection

@section('jstop')
  @parent
  <script>
    window.translations = {!!  json_encode($translations) !!};
  </script>

  @include('analytics')
@endsection

@section('js')
  @parent
  <script src="{{ cdn('/js/mobile/errors/404.js') }}"></script>
@endsection
