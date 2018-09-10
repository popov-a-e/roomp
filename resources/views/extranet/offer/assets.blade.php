@section('css')
  @parent
  <link rel="stylesheet" href="{{ cdn('/css/extranet/offer.css') }}"/>
@endsection

@section('js-public')
@endsection

@section('js')
  <script>
    window.translations = {!! json_encode($translations) !!};
  </script>
  <script src="{{ cdn('/js/extranet/offer.js') }}"></script>
@endsection
