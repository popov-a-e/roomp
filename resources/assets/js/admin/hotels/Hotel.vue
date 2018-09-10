<template>
  <div class='hotel' v-if='module_active && hotel'>
    <div class='card hotel-info-short'>
      <h2>{{ hotel ? hotel.name : `#${id}` }}</h2>
      <div v-if='hotel'>
        <div class='info'>
          <table class='plain' v-if='hotel && hotel.hotelier'>
            <tr>
              <td>Настоящее название</td>
              <td>{{hotel.regular_name}}</td>
            </tr>
            <tr>
              <td>Телефоны рецепции</td>
              <td>{{hotel.reception_phone}}</td>
            </tr>
            <tr>
              <td>E-mail для связи</td>
              <td>{{hotel.reception_email}}</td>
            </tr>
            <tr>
              <td>Адрес</td>
              <td>{{hotel.address_ru}}</td>
            </tr>
            <tr v-if="hotel.hotelier">
              <td>Управляющий</td>
              <td>{{hotel.hotelier.name}}<br>{{hotel.hotelier.phone}}</td>
            </tr>
            <tr v-if="hotel.contact_face">
              <td>Контактное лицо</td>
              <td>{{hotel.contact_face.position}}<br>{{hotel.contact_face.name}}<br>{{hotel.contact_face.phone}}</td>
            </tr>
            <tr>
              <td>Последняя активность (CM <span v-if="!hotel.hotelier.last_activity">не</span> подключен)</td>
              <td>{{hotel.hotelier.last_activity}}<br>{{hotel.hotelier.last_action}}</td>
            </tr>
            <tr>
              <td>Осталось Goody bags</td>
              <td><input v-model='goody_bags_left'/>
                <button v-if='goody_bags_changed' @click='updateGoodyBags'>Сохранить</button>
              </td>
            </tr>
            <tr>
              <td>Последнее изменение: </td>
              <td>{{ hotel.goody_bags_timestamp && hotel.goody_bags_timestamp + ' GMT' }}</td>
            </tr>
            <tr>
              <td>Необходимо Goody bags в ближайший месяц: </td>
              <td>{{ goody_bags_needed_next_month }}</td>
            </tr>
            <tr>
              <td>Удобства</td>
              <td>
                <p>Завтрак включен: {{ hotel.breakfast ? 'да' : 'нет' }}</p>
                <p>Паркинг: {{ hotel.amenities.some(amenity => amenity.id === 1) ? 'да' : 'нет' }}</p>
                <p>Халат: {{ roomsWithBathRobe.length > 0 ? roomsWithBathRobe.pluck('name').join(', ') : 'нет' }}</p>
              </td>
            </tr>
            <tr>
              <td>Комментарий</td>
              <td>
                <textarea :disabled="!comment_edited" @input="e => setHotelComment(e.target.value)" :value="hotel.comment"></textarea>
                <button @click="commentEdit" v-if="!comment_edited"><i class="fa fa-edit"></i></button>
                <button @click="saveHotelComment" v-else><i class="fa fa-save"></i></button>
              </td>
            </tr>
          </table>
        </div>
      </div>
      <router-link :to='`/hotels/${hotel.id}/info`'>Подробнее</router-link>
    </div>

    <div class='card hotel-rates'>
      <h2>Ставки нетто</h2>
      <router-link :to='`/hotels/${hotel.id}/rates`'><i class='icon-chevron-right'></i></router-link>
    </div>

    <div class='card integrations'>
      <h2>Интеграции</h2>
      <router-link :to='`/hotels/${hotel.id}/integrations`'><i class='icon-chevron-right'></i></router-link>
    </div>

    <div class="card integrations">
      <h2>История</h2>
      <router-link :to='`/hotels/${hotel.id}/logs`'><i class='icon-chevron-right'></i></router-link>
    </div>

    <div class='card integrations'>
      <h2>Оферта</h2>
      <router-link :to='`/hotels/${hotel.id}/offer`'><i class='icon-chevron-right'></i></router-link>
    </div>

    <div class='card prices'>
      <h2>Доступность</h2>
      <router-link :to='`/hotels/${hotel.id}/availability`'><i class='icon-chevron-right'></i></router-link>
    </div>
  <div class='card availability'>
      <h2>Цены</h2>
      <router-link :to='`/hotels/${hotel.id}/prices`'><i class='icon-chevron-right'></i></router-link>
    </div>
    <div class='card requisites'>
      <h2>Реквизиты</h2>
      <router-link :to='`/hotels/${hotel.id}/organization`'><i class='icon-chevron-right'></i></router-link>
    </div>

    <div class='card reconcilliation'>
      <h2>Сверка</h2>
      <router-link :to='`/hotels/${hotel.id}/reconcilliation`'><i class='icon-chevron-right'></i></router-link>
    </div>

    <div class='card policies'>
      <h2>Политики</h2>
      <router-link :to='`/hotels/${hotel.id}/policies`'><i class='icon-chevron-right'></i></router-link>
    </div>

    <div class='card hotel-rooms'>
      <h2>Номера в отеле</h2>
      <router-link :to='`/hotels/${hotel.id}/new`'>Добавить</router-link>
      <table v-if='hotel'>
        <tr>
          <th>ID</th>
          <th>Название</th>
          <th>Класс</th>
          <th>Размещение</th>
          <th>Количество</th>
        </tr>
        <tr v-for='room in hotel.rooms' v-on:click='selectRoom(room.id)'>
          <td>{{room.id}}</td>
          <td>{{room.name}}</td>
          <td>{{room.class.ru}}</td>
          <td>{{room.allotments.map(a => a.ru).join(' / ')}}
          </td>
          <td>{{room.number}}</td>
        </tr>
      </table>
    </div>

    <reservations class='hotel-reservations' v-if='hotel && hotel.id' :hotel='hotel.id'></reservations>
  </div>
</template>

<script>
  import Hotel from '../store/modules/card/Hotel';
  import Reservations from '../reservations/Reservations.vue';
  import router from './../router';

  export default {
    components: {Reservations},
    props: ['hotel_id'],
    methods: {
      initModule() {
        this.setID(this.hotel_id);
        this.load();
      },
      selectRoom(id) {
        router.push(`/hotels/${this.hotel_id}/${id}`);
      },
      ...Hotel.mapMethods()
    },
    computed: {
      roomsWithBathRobe() {
        return this.hotel.rooms.filter(room => room.amenities.some(amenity => amenity.id === 8))
      },
      ...Hotel.mapProps()
    },
    module: {Hotel}
  }
</script>