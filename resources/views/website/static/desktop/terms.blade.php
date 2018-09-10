@section('js')
  <script src="{{ cdn('/js/static/static.js') }}"></script>
@endsection

@section('css')
  @parent
  <link rel="stylesheet" href="{{ cdn('/css/static/static.css') }}"/>
@endsection

@section('content')
  <main
    v-on:click='Bus.$emit("click")'
    v-on:mouseup='Bus.$emit("mouseup")'
    v-on:mousemove='(e) => Bus.$emit("mousemove", e.pageX, e.pageY, e)'>

    <roomp-header
      :user='{{ $user }}'
      :locale='"{{$locale}}"'
      :currency="'{{ $currency }}'"
      :settings="{{ $settings }}"
    >
    </roomp-header>

    @include("static.{$locale}.terms")

    <roomp-footer></roomp-footer>
  </main>
@endsection