@section('jstop')
  @parent
  <script>window.locale = "{!! $locale !!}";</script>
  <script src="{{ cdn('/js/static/support.js') }}"></script>
@endsection

@section('css')
  @parent
  <link rel="stylesheet" href="{{ cdn('/css/static/support.css') }}"/>
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

    @include("static.$locale.support")

    <roomp-footer></roomp-footer>
  </main>
@endsection