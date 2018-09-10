<template>
  <div class='user-info'>
    <h1>
      {{ __("profile.my_profile") }}
    </h1>
    <div class='profile-data'>
      <div class='name'>
        <span>{{ __("profile.name") }}</span>
        <div>
          <input v-on:input='(e) => setName(e.target.value)' v-model='name' :placeholder='__("header.name")' />
        </div>
      </div>
      <div>
        <span>
          {{ __("profile.phone") }}
        </span>
        <phone-input :phone-init='phone' v-on:input='setPhone'></phone-input>
      </div>
      <div>
        <span>E-mail</span>
        <div>
          <input v-on:input='(e) => setEmail(e.target.value)' v-model='email'/>
        </div>
      </div>
      <div class='sex'>
        <span>{{ __("profile.gender") }}</span>
        <div>
          <label>
            <input v-on:change='(e) => setSex(e.target.value)' v-bind:checked='sex == 1' type='radio' name='sex'
                   value='1'/>
            <span>{{ __("profile.male") }}</span>
          </label>
          <label>
            <input v-on:change='(e) => setSex(e.target.value)' v-bind:checked='sex == 0' type='radio' name='sex'
                   value='0'/>
            <span>{{ __("profile.female") }}</span>
          </label>
        </div>
      </div>
      <div class='birthday'>
        <span>{{ __("profile.birthday") }}</span>
        <div>
          <select v-on:input='(e) => setDate(e.target.value)'>
            <option v-for='day in days' v-bind:selected='day == birthday.getDate()' v-bind:value='day'>{{ day }}
            </option>
          </select>
          <select v-on:input='(e) => setMonth(e.target.value)'>
            <option v-for='(month, i) in __("dates.full")' v-bind:value='i' v-bind:selected='i === birthday.getMonth()'>
              {{ month }}
            </option>
          </select>
          <select v-on:input='(e) => setYear(e.target.value)'>
            <option v-for='year in years' v-bind:selected='year == birthday.getFullYear()' v-bind:value='year'>
              {{ year }}
            </option>
          </select>
        </div>
      </div>
      <div>
        <button v-on:click='change'>{{ __('profile.apply_changes') }}</button>
      </div>
    </div>
    <div class='password'>
      <div>
        <span>{{ __("profile.new_password") }}</span>
        <div>
          <input type='password' v-on:input='(e) => setPassword(e.target.value)' v-model='password'/>
        </div>
      </div>
      <div>
        <span>{{ __("profile.repeat_password") }}</span>
        <div>
          <input type='password' v-on:input='(e) => setPasswordConfirmation(e.target.value)'
               v-model='password_confirmation'/>
        </div>
      </div>
      <div>
        <button v-on:click='changePassword'>{{ __("profile.change_password") }}</button>
      </div>
    </div>
  </div>
</template>

<script>
  import PhoneInput from './../../components/PhoneInput.vue';
  import {mapState, mapActions, mapMutations, mapGetters} from 'vuex';

  export default {
    components: {
      PhoneInput
    },
    data () {
      return {
        day: 0,
        month: 0,
        year: 0
      }
    },
    computed: {
      ...mapState(['birthday', 'name', 'phone', 'sex', 'email', 'password', 'old_password', 'password_confirmation']),
      ...mapState('Header', ['user']),
      ...mapGetters(['months', 'days', 'years'])
    },
    methods: {
      ...mapMutations(['initialize', 'setName', 'setPhone', 'setEmail', 'setDate', 'setMonth', 'setYear', 'setSex', 'setPassword', 'setPasswordConfirmation']),
      ...mapActions(['change', 'changePassword'])
    },
    created () {
      this.initialize(this.user);
    }
  }
</script>