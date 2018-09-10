@section('jstop')
  @parent
  <script>
    window.locale = "{{ $locale }}";
  </script>
  <script src="{{ cdn('/js/mobile/static/about.js') }}"></script>
@endsection

@section('css')
  @parent
  <link rel="stylesheet" href="{{ cdn('/css/mobile/static/about.css') }}"/>
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

    <div class="content">
      <nav>
        <router-link to="/">{{ __("footer.about_us") }}</router-link>
        <router-link to="/contacts">{{ __("footer.contacts") }}</router-link>
      </nav>

      <router-view></router-view>
    </div>
  </main>
@endsection