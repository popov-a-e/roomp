<template>
  <div class='card reservation' v-if='active'>
    <h2>Бронирование {{ id }}</h2>
    <div v-if='reservation'>
      <div class='info'>
        <h3>Общая информация</h3>
        <p>
          Код бронирования: {{ reservation.code }}
        </p>
        <p>
          Дата бронирования: {{ reservation.created_at }}
        </p>
        <p>
          Заезд/Выезд: {{ new Date(reservation.from).toVerboseDateString() }} - {{ new Date(reservation.till).toVerboseDateString() }}
        </p>
        <p v-if='reservation.channel'>
          Источник бронирования: {{ reservation.channel.name }}
        </p>
        <p>
          Комментарий: {{ reservation.comment }}
        </p>
      </div>
      <div class='hotel' v-if='reservation.hotel'>
        <h3>Отель</h3>
        <p>
          Название: {{ reservation.hotel.ru }}
        </p>
        <p>
          Адрес: {{ reservation.hotel.address_ru}}
        </p>
        <p>
          Телефон рецепции: {{ reservation.hotel.reception_phone }}
        </p>
        <p>
          Email рецепции: {{ reservation.hotel.reception_email }}
        </p>
      </div>
      <div class='guest' v-if='reservation.creator'>
        <h3>Гость</h3>
        <p>Имя: {{ reservation.creator.name }}</p>
        <p>Телефон: +{{ reservation.creator.phone}}</p>
        <p>Email: {{ reservation.creator.email}}</p>
      </div>
      <div class='finances'>
        <h3>Финансы</h3>
        <p v-for='o in reservation.occupancies'>
          {{o.room.class.ru}},
          {{o.allotment.ru}},
          {{o.adults}} гостей,
          {{o.children}} детей,
          {{o.infants}} младенцев,
          {{o.price}} руб.
        </p>
        <p>Итого: {{reservation.total}} руб.</p>
        <p>В том числе Roomp Rates: {{ reservation.total_rate }} руб.</p>
        <p v-if='reservation.channel.id > 1'>Комиссия OTA: {{ reservation.total * 0.15 }} руб.</p>
        <p>Net Rev: {{ reservation.total*(reservation.channel.id > 1 ? .85 : 1) - reservation.total_rate }} руб.</p>
      </div>
      <div class='log'>
        <h3>История изменений</h3>
        <p v-for='record in reservation.status_log'>{{record.status.name}}: {{record.user ? record.user.name : 'Unknown'}} ({{record.guard}}), {{record.timestamp}}</p>
      </div>
    </div>
  </div>
</template>

<script>
  import {mapState, mapActions, mapMutations, mapGetters} from 'vuex';
  import Reservation from '../store/modules/card/Reservation';
  import Card from '../mixins/Card';
  
  import { mapStateC, mapActionsC, mapMutationsC } from '../helpers/vuexc';


  export default {
    data () {
      return {
        name: 'Reservation',
        module: Reservation
      }
    },
    props: ['reservation_id'],
    methods: {
      initModule() {
        this.setCode(this.id);
        this.load();
      },
      ...mapActionsC(['load']),
      ...mapMutationsC(['setCode'])
    },
    computed: {
      id () {
        return this.reservation_id;
      },
      ...mapStateC(['reservation'])
    },
    mixins: [Card]
  }
</script>