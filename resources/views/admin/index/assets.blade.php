@section('css')
  @parent
  <link rel="stylesheet" href="{{ cdn('/css/admin/index.css') }}"/>
@endsection

@section('jstop')
  <script src='https://maps.googleapis.com/maps/api/js?key=AIzaSyCymuQZ09lnGN7QIhQWk1CAxOz5Ie-7iLQ'></script>
@endsection

@section('js')
  @translations(extranet,organization)
  <script>window.translations.currencies = {!! json_encode($currencies) !!}</script>
  <script>window.cloud_storage_root = '{{ cloud_storage_root() }}'</script>
  <script src="{{ cdn('/js/admin/index.js') }}"></script>
@endsection
