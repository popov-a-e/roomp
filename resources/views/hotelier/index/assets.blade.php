@section('css')
  @parent
  <link rel="stylesheet" href="{{ cdn('/css/hotelier/index.css') }}"/>
@endsection

@section('jstop')
  <script
    src='https://maps.googleapis.com/maps/api/js?key=AIzaSyCymuQZ09lnGN7QIhQWk1CAxOz5Ie-7iLQ'></script>

  <script>
    window.translations = {!! json_encode($translations) !!};
  </script>
@endsection

@section('js-public')
@endsection

@section('js')
  <script src="{{ cdn('/js/hotelier/index.js') }}"></script>
@endsection
