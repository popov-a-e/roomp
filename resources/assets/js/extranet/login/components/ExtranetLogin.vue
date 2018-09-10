<template>
  <div class='login'>
    <h1>{{__('extranet.login.header')}}</h1>
    <div>
      <label>
        <span class="type">{{__('extranet.login.email')}}</span>
        <input type='email' @input='e => setEmail(e.target.value)' :value='email'/>
        <span class="errors">{{ errors.email }}</span>
      </label>
      <label>
        <span class="type">{{__('extranet.login.password')}}</span>
        <input type='password' @input='e => setPassword(e.target.value)' :value='password'/>
        <span class="errors">{{ errors.password }}</span>
      </label>

      <button @click='login'>{{__('extranet.login.enter')}}</button>
    </div>
  </div>
</template>

<script>
  import {mapState, mapActions, mapMutations} from 'vuex';

  export default {
    computed: mapState(['email', 'password', 'errors']),
    methods: {
      ...mapMutations(['setEmail', 'setPassword']),
      ...mapActions(['login'])
    }
  }
</script>