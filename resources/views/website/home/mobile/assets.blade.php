@section('css')
  @parent
  <link rel="stylesheet" href="{{ cdn('/css/mobile/home.css') }}"/>
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
  <script src="{{ cdn('/js/mobile/home.js', true) }}"></script>
@endsection
