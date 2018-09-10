<template>
  <div class="offer-status" v-if="offer && hotel">
    <h2 v-if="!offerVisible">{{ hotel.regular_name }}</h2>
    <div class="offer-state" v-if="!offerVisible">
      <h4>Оферта успешно сгенерирована!</h4>
      <p>Отельер проходил по ссылке: {{ hotelier.last_visit ? hotelier.last_visit : 'нет' }} </p>
      <p>Отельер зарегистрирован: {{ hotelier.email ? 'да' : 'нет' }} </p>
      <p>Отельер принял оферту: {{ offer.accept_date ? 'да' : 'нет' }}</p>
    </div>
    <div class="controls">
      <template v-if="!offerVisible">
        <a :href="`/offer/static/${offer.filename}`" target="_blank">Посмотреть оферту</a>
        <button @click="$router.push(`/onboarding/${hotel.id}/edit`)">Изменить данные</button>
      </template>
      <button class="hide-offer" @click="hideOffer" v-else>Скрыть оферту</button>
    </div>
    <iframe class="offer" v-if="offerVisible" :src="`/offer/static/${offer.filename}`"></iframe>
  </div>
</template>

<script>
  import {mapActions, mapMutations, mapState, mapGetters} from 'vuex';

  export default {
    data() {
      return {}
    },
    props: ['hotel_id'],
    methods: {
      ...mapMutations('Hotel/OfferStatus', ['setOffer', 'showOffer', 'hideOffer', 'setHotelier', 'clear']),
      ...mapMutations('Hotel', ['edit']),

      ...mapActions('Hotel', ['load'])
    },
    computed: {
      ...mapState('Hotel/OfferStatus', ['offerVisible', 'offer', 'hotelier']),
      ...mapState('Hotel', ['hotel']),
      ...mapGetters('Hotel', ['stage']),
    },
    beforeRouteEnter(to, from, next) {
      next(vm => {
        vm.clear();
        vm.load(vm.hotel_id).then(() => {
          if (vm.stage === 'initial' || vm.stage === 'deleted' || vm.stage === 'thinking') {
            vm.$router.push(`/onboarding/${vm.hotel.id}/select_status`)
          }/*
          else if (vm.stage === 'signing') {
            vm.$router.push(`/onboarding/${vm.hotel.id}/edit`)
          }*/
          else {
            vm.setOffer(vm.hotel.offer);
            vm.setHotelier(vm.hotel.hotelier);
          }
        });
      });
    },
    created() {

    }
  }
</script>