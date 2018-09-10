<template>
  <form @submit.prevent="submit">
    <h1>{{ __('extranet.new_hotel.name', {name: hotel.regular_name}) }}</h1>
    <div>
      <label>
        <span>{{ __('extranet.new_hotel.address') }}</span>
        <input v-model="address"/>
      </label>
      <label>
        <span>{{ __('extranet.new_hotel.room_number') }}</span>
        <input type="number" min="0" max="99999" v-model="room_number"/>
      </label>
      <label>
        <span>{{ __('extranet.new_hotel.reception_phone') }}</span>
        <input v-model="reception_phone"/>
      </label>
      <label>
        <span>{{ __('extranet.new_hotel.reception_email') }}</span>
        <input type="email" v-model="reception_email"/>
      </label>

      <p ><input type="checkbox" v-model="acceptance" /><span v-html="__('extranet.new_hotel.agreement', {link: '/docs/offer'})"></span></p>

      <button :disabled="!acceptance || loading" :class="{loading}" type="submit">
        <template v-if="!loading">
          {{__('extranet.new_hotel.apply')}}
        </template>
        <template v-else>
          <i class="fa fa-circle-notch fa-spin"></i>
        </template>
      </button>
    </div>
  </form>
</template>

<script>
  import {mapState, mapActions, mapGetters, mapMutations} from 'vuex';

  export default {
    props: ['hotel'],
    data() {
      return {
        address: this.hotel.address,
        room_number: this.hotel.room_number,
        reception_phone: this.hotel.reception_phone,
        reception_email: this.hotel.reception_email,
        acceptance: false,
        loading: false,
        errors: {
          address: null,
          room_number: null,
          reception_phone: null,
          reception_email: null,
          acceptance: null,
        }
      }
    },
    methods: {
      submit() {
        this.loading = true;
        axios.post('/offer/accept', this._data).then(response => {
          this.loading = false;
          window.location.href = '/';
        }).catch(error => {
          this.loading = false;
          alert(error.response.data);
        });
      }
    },
  }
</script>  