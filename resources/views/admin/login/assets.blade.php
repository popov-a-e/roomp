@section('css')
  @parent
  <link rel="stylesheet" href="{{ cdn('/css/admin/login.css') }}"/>
@endsection

@section('js')
  @translations()
  <script src="{{ cdn('/js/admin/login.js') }}"></script>
@endsection
