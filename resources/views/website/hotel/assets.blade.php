@section('css')
  @parent
  <link rel="stylesheet" href="{{ cdn('/css/hotel.css') }}"/>
@endsection

@section('jstop')
  <script
    src='https://maps.googleapis.com/maps/api/js?key=AIzaSyCymuQZ09lnGN7QIhQWk1CAxOz5Ie-7iLQ&libraries=places'></script>

  <script>
    window.translations = {!!  json_encode($translations) !!};
    window.cloud_storage_root = '{{ $cloud_storage_root }}';
  </script>
  @include('analytics')
@endsection

@section('js-public')
  <script>
    window. ChatraGroupID = 'wwECX8NQYENEkW62P';
  </script>
  @parent
@endsection

@section('js')
  <script src="{{ cdn('/js/hotel.js') }}"></script>
@endsection
