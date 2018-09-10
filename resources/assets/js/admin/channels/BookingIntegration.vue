<template>
  <div class="booking_integration">
    <h5>Интеграция с Booking.com</h5>

    <div class="ota">
      <div class="channel_create">
        <button v-if="!channel_id" @click="createOTA">Создать канал в WuBook</button>

        <p v-if="channel_id">Канал создан! ID: {{ channel_id }}</p>
      </div>
      <div class="start_procedure" v-if="channel_id">
        <input v-if="!procedure_started" v-model="booking_id" placeholder="ID отеля в Booking.com"/>
        <a v-if="!procedure_started" href="/public/img/admin/ID.JPG" target="_blank">Где посмотреть ID</a>
        <button v-if="!procedure_started" @click="startProcedure">Начать процедуру интеграциии</button>

        <p v-if="procedure_started">ID отеля в Booking.com: {{ booking_id }}</p>
      </div>
      <div class="confirm_activation" v-if="procedure_started && !channel_inited">
        <div class="booking_algorithm" v-if="!activation_confirmed">
          <ol>
            <li>Открыть страницу отеля в экстранете booking.com</li>
            <li>Открыть страницу управления Channel manager <a href="/public/img/admin/channel_manager_btn.JPG"
                                                               target="_blank">Изображение</a></li>
            <li>Нажать Start <a href="/public/img/admin/channel_manager_start.JPG" target="_blank">Изображение</a></li>
            <li>Выбрать WuBook из списка и продолжить <a href="/public/img/admin/channel_manager_wubook.JPG"
                                                         target="_blank">Изображение</a></li>
            <li>Поставить галочку и продолжить <a href="/public/img/admin/channel_manager_next.JPG"
                                                  target="_blank">Изображение</a></li>
            <li>Вы должны увидеть следующее окно <a href="/public/img/admin/channel_manager_3.JPG"
                                                    target="_blank">Изображение</a></li>

            <p>После этого в течение 5-10 минут на почту должно прийти <a
              href="/public/img/admin/channel_manager_mail.JPG" target="_blank">сообщение</a>. Когда оно придет, кликните "Подтвердить активацию".
            </p>
          </ol>
        </div>

        <button v-if="!activation_confirmed" @click="confirmActivation">Подтвердить активацию</button>

        <p v-if="activation_confirmed">Канал активирован</p>
      </div>
      <div class="init_channel" v-if="activation_confirmed && (!mapping || Object.keys(mapping).length === 0)">
        <div class="booking_algorithm" v-if="!channel_inited">
          <ol>
            <li>Необходимо открыть страницу управления Channel manager. <a
              href="/public/img/admin/channel_manager_btn.JPG" target="_blank">Изображение</a></li>
            <li>Нажмите на кнопку подтверждения соединения. <a href="/public/img/admin/channel_manager_end.JPG"
                                                               target="_blank">Изображение</a></li>
            <li>Вы должны увидеть следующее окно: <a href="/public/img/admin/channel_manager_success.JPG"
                                                     target="_blank">Изображение</a></li>

            <p>После этого нажмите кнопку "Инициировать канал"</p>
          </ol>
        </div>

        <button v-if="!channel_inited" @click="initChannel">Инициировать канал</button>

        <p v-if="channel_inited">Канал работает! Можно приступать к сопоставлению комнат</p>
      </div>

      <div class="mapping" v-if="mapping && Object.keys(mapping).length > 0">
        <button @click="initChannel" class='channel_reset'>Перенастроить канал</button>
        
        <div class="upload_rooms">
          <button @click="syncWubookRooms"
                  :class="{highlighted: !wubook_rooms || wubook_rooms.length === 0}">Загрузить комнаты Roomp в WuBook
          </button>
          <p v-if="!wubook_rooms || wubook_rooms.length === 0">Комнаты Roomp не загружены в WuBook</p>
          <p v-else>Комнаты Roomp загружены в WuBook</p>
          
          <button class='sync' @click='syncRoomsRates'>
            Синхронизировать тарифы и комнаты с Booking.com
          </button>
        </div>
        <div class="mappings">
          <label class="th-booking">Комната в Booking</label><label class="th-roomp">Комната в Roomp</label>
          <label class="tr" v-for="(wubook, booking) in mapping" >
            <span>{{bookingRoomName(booking)}}: {{booking}}</span>
            <select @input="e => setMappingRoom({booking, wubook: e.target.value})">
              <option :value="null" :selected="wubook === null" disabled>Сопоставление не задано</option>
              <option v-for="room in wubook_rooms" :value="room.wubook_room.rid"
                      :selected="wubook !== null && Number(wubook) === Number(room.wubook_room.rid)">
                {{room.name}}: {{room['class'].ru}}, {{room.allotments.map(a => a.ru).join(',') }} : {{ room.wubook_room.code }}
              </option>
            </select>
          </label>
          <button @click="setRooms" :class="room_upload_status">{{roomUploadStatus}}</button>
        </div>
        <div class='rate-mappings'>
          <label class="th-booking">Тариф в Booking</label><label class="th-roomp">Число гостей</label><label class="th-roomp" style='grid-column: 3;'>План ограничений</label>
          <label class="tr rates" v-for="(wubook, booking) in rate_mapping" >
            <span>{{bookingRateName(booking)}}: {{booking}}</span>
            <select @input="e => setMappingRate({booking, wubook: e.target.value})">
              <option :value="null" :selected="wubook.pplan === null" disabled>Сопоставление не задано</option>
              <option v-for="rate in wubook_rates" :value="rate.rid"
                      :selected="wubook !== null && Number(wubook.pplan) === Number(rate.rid)">
                {{ rate.occupancy }} гостей, {{ wubook_rate_plans.where('id', rate.rate_id).name }}, {{ rate.breakfast ? 'завтрак' : 'без завтрака' }}
              </option>
            </select>
  
            <select @input="e => setMappingRatePlan({booking, wubook: e.target.value})">
              <option :value="null" :selected="wubook.rplan === null" disabled>Сопоставление не задано</option>
              <option v-for="rate_plan in wubook_rate_plans" :value="rate_plan.wubook_id"
                      :selected="wubook !== null && Number(wubook.rplan) === Number(rate_plan.wubook_id)">
                {{ rate_plan.name }}
              </option>
            </select>
          </label>
          <button @click="setRates" :class="rate_upload_status">{{rateUploadStatus}}</button>
        </div>
        <p style="float: left; clear:both;" v-if="active">Канал работает</p>
        <p style="float: left; clear:both;" v-else class="warning">Канал не работает!</p>
      </div>
    </div>
    <div class='premium_program' v-if='connection'>
      <label><input type='checkbox' v-model='is_premium'><span>Привилегированная программа</span></label> <button v-if='!connection || connection.premium_program !== is_premium' @click='setPremiumProgram'>Сохранить</button>
    </div>
  </div>
</template>

<script>
  import BookingIntegration from '../store/modules/BookingIntegration';
  import {mapState, mapMutations, mapActions, mapGetters} from 'vuex';

  export default {
    props: ['hotel-i-d'],
    module: {BookingIntegration},
    methods: {
      ...BookingIntegration.mapMethods(),
      initModule() {
        this.setHotelID(this.hotelID);
        this.load();
        this.loadWubookRooms();
        this.loadWubookRates();
      },
      bookingRoomName(id) {
        return this.booking_rooms.where('booking_room_id', Number(id)).booking_name;
      },
      bookingRateName(id) {
        return this.booking_rates.where('booking_rate_id', Number(id)).booking_name;
      },
    },
    computed: {
      ...BookingIntegration.mapProps()
    },
    created() {
      this.$watch('hotelID', e => e && this.$emit('module_updated', e));
      this.$emit('module_updated');
    }
  }
</script>