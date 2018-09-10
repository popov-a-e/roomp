@section('css')
  @parent
  <link rel="stylesheet" href="{{ cdn('/css/mobile/hotel.css') }}"/>
@endsection

@section('jstop')
  <script
    src='https://maps.googleapis.com/maps/api/js?key=AIzaSyCymuQZ09lnGN7QIhQWk1CAxOz5Ie-7iLQ&libraries=places'></script>
  <link rel="dns-prefetch" href="http://i.roomp.co" />

  <script>
    window.translations = {!!  json_encode($translations) !!};
    window.cloud_storage_root = '{{ $cloud_storage_root }}';
  </script>

  @include('analytics')
@endsection

@section('js')
  <script src="{{ cdn('/js/mobile/hotel.js') }}"></script>
@endsection
