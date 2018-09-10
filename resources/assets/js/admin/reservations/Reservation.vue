<template>
  <div class='card reservation' v-if='module_active && reservation'>
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
          Заезд/Выезд: {{ new Date(reservation.from).toVerboseDateString() }} - {{ new Date(reservation.ftill).toVerboseDateString() }}
        </p>
        <p v-if='reservation.channel'>
          Источник бронирования: {{ reservation.channel.name }}
        </p>
        <p>
          Гость: {{ reservation.guest_name }}
          <button v-if="!ostrovok_phone && reservation.channel && reservation.channel.name === 'Ostrovok'" @click='getOstrovokPhone'>Получить номер гостя с островка</button>
          <span v-else-if="reservation.channel && reservation.channel.name === 'Ostrovok'">{{ostrovok_phone}}</span>
        </p>
        <p style="font-weight: bolder;">
          Оплата: {{ reservation.status_log[0].status.online ? "Онлайн" : "В отеле"}}
        </p>
        <p>
          Комментарий: {{ reservation.comment }}
        </p>
        <a :href='`https://roomp.co/pay_with_confirmation/${reservation.code}?token=${reservation.token}`'>Ссылка для оплаты</a>
      </div>
      <div class='hotel' v-if='reservation.hotel'>
        <h3>Отель</h3>
        <p>
          Название: <a :href='`#/hotels/${reservation.hotel.id}`'>{{ reservation.hotel.ru }}</a>
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
          <a :href='`#/hotels/${reservation.hotel.id}/${o.room.id}`'>{{o.room.class.ru}}, {{o.allotment.ru}}</a>,
          {{o.adults}} гостей, {{o.children }} детей, {{o.infants}} младенцев,
          {{o.price}} руб., {{o.breakfast_included ? 'завтрак включен' : 'без завтрака'}}{{o.is_nr ? ', NR' : ''}}{{ o.tariff ? ', ' + o.tariff : ''}}
        </p>
        <p v-if='reservation.promo_code'>Скидка по промокоду {{reservation.promo_code.code }}: {{reservation.discount}} руб.</p>
        <p>Итого: {{reservation.total}} руб.</p>
        <p>В том числе Roomp Rates: {{ reservation.total_rate }} руб.</p>
        <p v-if='reservation.channel && reservation.channel.id > 1'>Комиссия OTA: {{ reservation.total * 0.15 }} руб.</p>
        <p v-if='reservation.channel && reservation.channel.id' >Net Rev: {{ reservation.total*(reservation.channel.id > 1 ? .85 : 1) - reservation.total_rate }} руб.</p>
      </div>
      <div class='log'>
        <h3>История изменений</h3>
        <p v-for='record in reservation.status_log'>{{record.status.name}}: {{record.user ? record.user.name : 'Unknown'}} ({{record.guard}}), {{record.timestamp}}</p>
        <label>
          <span>Установить статус:</span>
          <button @click="setActive(false)">Оплата в отеле</button>
          <button @click="setActive(true)">Оплата онлайн</button>
        </label>
      </div>

      <div class="admin_comment">
        <h3>Параметры администратора</h3>
        <textarea :disabled="!edited" style="float: left; margin-right: 10px;" v-model="admin_comment"></textarea>
        <button style="float: left;" @click="toggleEdit"><i class="fa" :class="{'fa-edit': !edited, 'fa-save': edited}"></i></button>


        <div class="controls">
          <button style="float: left;" @click="setAdminComment">Сохранить</button>
          <button @click="miss">Проставить незаезд</button>
          <button @click="cancel">Отменить</button>
          <button @click="overbooking">Овербукинг</button>
          <button @click="resendEmail">Повторно отправить сообщение пользователю</button>
          <button @click="resendEmailToHotelier">Повторно отправить сообщение отельеру</button>
          <button @click="channelPush">Обновить бронирование в канале</button>
          <button @click="del" class='warning'>Удалить</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import Reservation from '../store/modules/card/Reservation';
  
  export default {
    module: {Reservation},
    props: ['reservation_id'],
    methods: {
      initModule() {
        this.setCode(this.id);
        this.load();
      },
      ...Reservation.mapMethods(),
    },
    computed: {
      id () {
        return this.reservation_id;
      },
      ...Reservation.mapProps(),
    }
  }
</script>