@section('css')
  @parent
  <link rel="stylesheet" href="{{ cdn('/css/admin/login.css') }}"/>
@endsection

@section('jstop')
  @parent
  <script>
    window.translations = {!! json_encode($translations) !!};
  </script>
@endsection

@section('js-public')
@endsection

@section('js')
  <script src="{{ cdn('/js/bd/login.js') }}"></script>
@endsection
