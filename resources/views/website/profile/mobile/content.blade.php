<main
  v-on:click='Bus.$emit("click")'
  v-on:mouseup='Bus.$emit("mouseup")'
  v-on:mousemove='(e) => Bus.$emit("mousemove", e.pageX, e.pageY, e)'
>
  <roomp-header
    :user='{{ $user }}'
    :locale='"{{$locale}}"'
    :currency="'{{ $currency }}'"
    :settings="{{ $settings }}"
  >
  </roomp-header>

  <nav>
    <router-link to="/">{{ __("header.profile") }}</router-link>
    <router-link to="/reservations">{{ __("header.my_bookings") }}</router-link>
  </nav>

  <div class='tabs'>
    <router-view></router-view>
  </div>
</main>