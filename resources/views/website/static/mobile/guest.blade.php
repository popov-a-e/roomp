@section('js')
  <script src="{{ cdn('/js/mobile/static/static.js') }}"></script>
@endsection

@section('css')
  @parent
  <link rel="stylesheet" href="{{ cdn('/css/mobile/static/static.css') }}"/>
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

    @include("static.{$locale}.guest")

    <roomp-footer></roomp-footer>
  </main>
@endsection