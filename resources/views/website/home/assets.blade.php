@section('css')
  @parent
  <link rel="stylesheet" href="{{ cdn('/css/home.css') }}"/>
@endsection

@section('jstop')
  @parent
  @include('analytics')
@endsection

@section('js')
  <script src="{{ cdn('/js/home.js') }}"></script>
@endsection
