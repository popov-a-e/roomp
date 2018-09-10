<main
  v-on:click='Bus.$emit("click")'
  v-on:mouseup='Bus.$emit("mouseup")'
  v-on:mousemove='(e) => Bus.$emit("mousemove", e.pageX, e.pageY, e)'>

  <hotelier-header :user='{{ $user }}' :hotels='{{ $hotels }}' :hotel='{{ $hotel }}'></hotelier-header>

  <div class='content'>
    <unchecked-reservations :reservations-unchecked='{{ $reservations }}'></unchecked-reservations>
  </div>
</main>