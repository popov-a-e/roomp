<main
  v-on:click='Bus.$emit("click")'
  v-on:mouseup='Bus.$emit("mouseup")'
  v-on:mousemove='(e) => Bus.$emit("mousemove", e.pageX, e.pageY, e)'>

  <hotelier-header :user='{{ $user }}' :hotels='{{ $hotels }}' :hotel='{{ $hotel }}' :rate="{{$rate ?? 'null'}}"></hotelier-header>

  <keep-alive>
    <router-view></router-view>
  </keep-alive>

  <hotelier-footer></hotelier-footer>

</main>