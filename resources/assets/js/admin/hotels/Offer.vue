<template>
  <div class='offer card' v-if="module_active">
    <h2>Оферта</h2>

    <template v-if="!offer">
      <p>Оферты нет</p>
      <button @click="create">Создать</button>
    </template>
    <template v-else-if="!offer.filename">
      <p>Номер оферты: {{ offer.id }}</p>
      <p>Оферта не сгенерирована</p>
      <a target="_blank" :href="`#/hotels/${hotel_id}/organization`">Проверить реквизиты</a>
      <button @click="generate">Сгенерировать</button>
    </template>
    <template v-else>
      <p>Номер оферты: {{ offer.id }}</p>
      <p>Ссылка на оферту: <a :href="`/hotels/${hotel_id}/offer/file`">ссылка</a></p>
      <p v-if="offer.registration_token">Ссылка на регистрацию: <a :href="`https://extranet.roomp.co/register/${hotel_id}?token=${offer.registration_token}`">ссылка</a></p>
      <button @click="regenerate">Пересоздать оферту</button>
      <button @click="register">Начать регистрацию</button>
      <button @click="resetPassword">Сбросить пароль</button>
      <p>Отельер проходил по ссылке: {{ offer.last_visit || 'нет' }}</p>
      <p>Отельер зарегистрирован: {{ offer.registered ? 'да' : 'нет' }}</p>
      <template v-if="!offer.terminated_at">
        <p>Оферта <template v-if="!offer.accept_date">не</template> принята</p>
        <button @click="terminate">Расторгнуть договор</button>
      </template>

      <template v-else>
        <p>Договор расторгнут {{ offer.terminated_at }}</p>
      </template>
    </template>
  </div>
</template>

<script>
  import Bus from '../../Bus';
  import Offer from '../store/modules/Offer';

  export default {
    props: ['hotel_id'],
    module: {Offer},
    computed: {
      ...Offer.mapProps(),
    },
    methods: {
      ...Offer.mapMethods(),
      initModule() {
        this.setHotelID(this.hotel_id);
        this.load();
      }
    },
  }
</script>