@section('css')
  @parent
  <link rel="stylesheet" href="{{ cdn('/css/mobile/profile.css') }}"/>
@endsection

@section('jstop')
  @parent

  <script>
    window.translations = {!!  json_encode($translations) !!};
  </script>

  @include('analytics')
@endsection

@section('js')
  <script src="{{ cdn('/js/mobile/profile.js') }}"></script>
@endsection