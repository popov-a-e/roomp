<template>
  <div class="amenities">
    <h2>{{__('search.hotel_amenities')}}</h2>
    <div>
      <cbutton
        v-for="amenity in hotelAmenitiesArray"
        :name="amenity.name"
        :id="amenity.id"
        :value="hotelAmenities.indexOf(amenity.id) >= 0"
        v-on:input="toggleHotelAmenity"
      >
      </cbutton>
    </div>
    <hr>
    <h2>{{__('search.room_amenities')}}</h2>
    <div>
      <cbutton
        v-for="amenity in roomAmenitiesArray"
        :name="amenity.name"
        :id="amenity.id"
        :value="roomAmenities.indexOf(amenity.id) >= 0"
        v-on:input="toggleRoomAmenity"
      >
      </cbutton>
    </div>
    <hr>
    <h2>{{__('search.payment_method')}}</h2>
    <div>
      <cbutton
        :name='__("search.payment_online")'
        :id='"payment_online"'
        :value='online'
        @input='togglePaymentOnline'
      >
      </cbutton>
      <cbutton
        :name='__("search.payment_by_cash")'
        :id='"payment_by_cash"'
        :value='cash'
        @input='togglePaymentByCash'
      >
      </cbutton>
      <cbutton
        :name='__("search.payment_by_card")'
        :id='"payment_by_card"'
        :value='card'
        @input='togglePaymentByCard'
      >
      </cbutton>
    </div>
  </div>
</template>

<script>
  import Cbutton from '../../../components/Cbutton.vue';
  import Bus from '../../../Bus';
  import {mapMutations, mapState} from 'vuex';

  export default {
    methods: {
      ...mapMutations('Filters',['toggleHotelAmenity', 'toggleRoomAmenity', 'togglePaymentByCard', 'togglePaymentByCash', 'togglePaymentOnline'])
    },
    computed: mapState({
      hotelAmenities: state => state.Filters.hotel_amenities,
      roomAmenities: state => state.Filters.room_amenities,
      online: state => state.Filters.payment_online,
      cash: state => state.Filters.payment_by_cash,
      card: state => state.Filters.payment_by_card,
      hotelAmenitiesArray: state => state.BackendData.hotelAmenities,
      roomAmenitiesArray: state => state.BackendData.roomAmenities
    }),
    components: {
      Cbutton
    }
  }
</script>