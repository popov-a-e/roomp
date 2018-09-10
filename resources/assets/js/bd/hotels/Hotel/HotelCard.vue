<template>
  <a class="hotel" :href="`#/onboarding/${hotel.id}`">
    <h4>{{hotel.regular_name}}</h4>
    <p>
      <i class="fa fa-building"></i>
      <span>{{city}}</span>
    </p>
    <p>
      <i class="fa fa-map-marker"></i>
      <span>{{hotel.address_ru}}</span>
    </p>
    <p>
      <i class="fa fa-phone"></i>
      <span>{{contactPhone}}</span>
    </p>
    <progress-bar :progress="completedBy">
      {{ completedBy }}
    </progress-bar>
  </a>
</template>

<script>
  import ProgressBar from '../../components/ProgressBar.vue';

  import {mapState} from 'vuex';

  export default {
    components: {
      ProgressBar
    },
    data () {
      return {}
    },

    props: ['hotel'],
    computed: {
      completedBy() {
        const hotel = this.hotel;
        const organization = except(hotel.organization, ['id', 'created_at', 'updated_at', 'contract_n', 'contract_date', 'contract_signee', 'contract_scan', 'contacts']);
        const hotelFields = ['regular_name', 'city_id', 'address_ru', 'reception_email'];

        let sum = 0;
        let completed = 0;

        hotelFields.forEach(field => {
          if (hotel[field]) completed++;
          sum ++;
        });

        Object.keys(organization).forEach(field => {
          if (organization.form === 'ИП' && ['OKPO', 'KPP', 'CEO', 'CEO_short_name'].indexOf(field) >= 0) return;

          if (organization[field]) completed++;
          sum ++;
        });

        return Math.floor(100 * completed / sum);
      },
      ...mapState('Meta', ['cities']),
      city() {
        const city = this.cities.where('id', this.hotel.city_id);

        return city ? city.ru : '';
      },
      contactPhone () {
        const org = this.hotel.organization;
        return org && org.contacts && org.contacts[0] && org.contacts[0].phone;
      }
    },
    methods: {
      toHotel() {
        this.$router.push(`/onboarding/${this.hotel.id}`);
      },
    }
  }
</script>