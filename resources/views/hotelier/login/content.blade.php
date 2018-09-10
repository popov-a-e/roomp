<main
  v-on:click='Bus.$emit("click")'
  v-on:mouseup='Bus.$emit("mouseup")'
  v-on:mousemove='(e) => Bus.$emit("mousemove", e.pageX, e.pageY, e)'>
  <hotelier-header></hotelier-header>
  <hotelier-login></hotelier-login>
  <hotelier-footer></hotelier-footer>
</main>