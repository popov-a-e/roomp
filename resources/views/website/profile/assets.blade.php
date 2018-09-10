@section('css')
  @parent
  <link rel="stylesheet" href="{{ cdn('/css/profile.css') }}"/>
@endsection

@section('jstop')
  @parent

  <script>
    window.translations = {!!  json_encode($translations) !!};
  </script>
@endsection


@section('js')
  <script src="{{ cdn('/js/profile.js') }}"></script>

@endsection