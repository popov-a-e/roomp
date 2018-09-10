<template>
  <div class="card new-reservation" v-if="module_active && hotels">
    <h2>Новое бронирование</h2>
    <div>
      <scrollable-select
        :get="() => hotel_id"
        :set="setHotelID"
        :values="hotels"
      >
      </scrollable-select>
      <form v-if="hotel_id > 0" class="module" @submit="book">
        <div class='dates'>
          <div class='placeholder'>
            Выберите даты

          </div>
          <div class='form' v-on:click.prevent.stop='datesClick'>
            <div class='from'>
              <a class="date" href="javascript:void(0);"
                 v-on:click="toggleDatepicker('from')"
              >{{ fromFormatted }}</a>

              <a class="day" href="javascript:void(0);"
                 v-on:click="toggleDatepicker('from')"
              >{{ fromDayOfWeek }}</a>
            </div>
            <span class='nights' v-on:click="toggleDatepicker('from')">
              {{ nightsFormatted }}
            </span>
            <div class='till'>
              <a class="date" href="javascript:void(0);"
                 v-on:click="toggleDatepicker('till')"
              >{{ tillFormatted }}</a>

              <a class="day" href="javascript:void(0);"
                 v-on:click="toggleDatepicker('till')"
              >{{ tillDayOfWeek }}</a>
            </div>

            <datepicker
              v-if="inputDate"
              :from="from"
              :till="till"
              :type="inputDate"
              v-on:input="setDates"
            ></datepicker>
          </div>
        </div>

        <div class="policy" v-if="policy">
          <h5>Политика отеля</h5>
          <p>Число доп. спальных мест: {{ policy.bed_number }}</p>
          <p>Стоимость размещения взрослого: {{ policy.price_adults === null ? "Не разрешено" : policy.price_adults
            }}</p>
          <p>Стоимость размещения ребенка от 2 до {{ policy.age_children
            }} лет: {{ policy.price_children === null ? "Не разрешено" : policy.price_children }}</p>
          <p>
            Стоимость размещения ребенка до 2 лет: {{ policy.price_infants === null ? "Не разрешено" : policy.price_infants
            }}</p>
        </div>

        <div class="rooms" v-if="rooms && rooms.length > 0 && policy">
          <table>
            <tr>
              <th>Название</th>
              <th>Класс</th>
              <th>Цена</th>
              <th>Доступно</th>
              <th></th>
            </tr>
            <tr v-for="room in rooms">
              <td>{{ room.name }}</td>
              <td>{{ className(room.room_class_id) }}</td>
              <td v-html="toCurrency(room.prices[1], policy.currency)"></td>
              <td>{{ room.count }}</td>
              <td>
                <button type="button" v-if="room.count > 0" @click="addRoom(room)">+</button>
              </td>
            </tr>
          </table>
        </div>

        <div class="rooms_selected" v-if="policy && selected_rooms.length > 0">
          <table>
            <tr>
              <th>Название</th>
              <th>Класс</th>
              <th>Размещение</th>
              <th>Взрослых</th>
              <th v-if="policy.price_children">Детей от 2 до {{ policy.age_children }} лет</th>
              <th v-if="policy.price_infants">Детей до 2 лет</th>
              <th>Сумма</th>
            </tr>
            <tr v-for="room in selected_rooms">
              <td>{{ room.room.name }}</td>
              <td>{{ className(room.room.room_class_id) }}</td>
              <td>
                <select @input="e => setAllotment({room, value: Number(e.target.value)})">
                  <option v-for="allotment in room.allotmentList" :value="allotment.id">{{allotment.name}}</option>
                </select>
              </td>
              <td>
                <select @input="e => setAdultNumber({room,value: Number(e.target.value)})">
                  <option v-for="(value, id) in room.adultsArray" :id="id"> {{value}}</option>
                </select>
              </td>
              <td v-if="policy.price_children">
                <select @input="e => setChildrenNumber({room, value: Number(e.target.value)})">
                  <option v-for="(value, id) in room.childrenArray" :id="id"> {{value}}</option>
                </select>
              </td>
              <td v-if="policy.price_infants">
                <select @input="e => setInfantNumber({room, value: Number(e.target.value)})">
                  <option v-for="(value, id) in room.infantArray" :id="id"> {{value}}</option>
                </select>
              </td>
              <td>
                <input type="number" :disabled="room.priceManual === false"
                       @input="e => setPriceManual({room, value: e.target.value})" :value="room.price"/>
                <button type="button"
                        @click="e => room.togglePriceManual()">
                  <i :class="{fa: true,'fa-edit': !room.priceManual, 'fa-save': room.priceManual}"></i>
                </button>
              </td>
              <td>
                <button type="button" @click="deleteRoom(room)">-</button>
              </td>
            </tr>
          </table>
        </div>

        <div class="guest_data">
          <input name="name" placeholder="Имя и фамилия" v-model="name" required/>
          <input name="phone" type="number" required placeholder="Телефон" v-model="phone"/>
          <input name="email" type="email" required placeholder="E-mail" v-model="email"/>
          <textarea placeholder="Комментарий" v-model="comment"></textarea>
        </div>

        <div class="controls" v-if="selected_rooms && selected_rooms.length > 0">
          <label><input type="checkbox" v-model="online"/> Онлайн</label>
          <div class="total">
            <p v-html="`Итого: ${nightsFormatted}, ${toCurrency(sum, policy.currency)}`"></p>
          </div>

          <button type="submit">Забронировать</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
  import ScrollableSelect from '../components/ScrollableSelectWithInput.vue';
  import {mapState, mapMutations, mapActions, mapGetters} from 'vuex';
  import ReservationCreator from '../store/modules/ReservationCreator';
  import Datepicker from '../../components/Datepicker/Datepicker.vue';
  import Bus from '../../Bus';

  export default {
    data() {
      return {
        inputDate: false
      }
    },
    components: {Datepicker, ScrollableSelect},
    module: {ReservationCreator},
    methods: {
      initModule() {
        this.getHotels();

        if (this.room_classes.length === 0) {
          this.$store.dispatch('Meta/loadAll');
        }
      },
      ...ReservationCreator.mapMethods(),
      toggleDatepicker(dir) {
        this.inputDate = this.inputDate === dir ? false : dir;
      },
      setDates(e) {
        if (this.from && e.value.toISODateString() < this.from.toISODateString()) {
          this.setFrom(null);
          this.setTill(null);
          e.type = this.inputDate = 'from';
        }

        if (e.type === 'from') {
          this.setFrom(e.value);
          if (this.till && e.value.toISODateString() >= this.till.toISODateString()) {
            this.setTill(null);
          }
          this.inputDate = 'till';
        } else {
          if (this.from) this.inputDate = false;
          this.setTill(e.value);
        }
      },
      datesClick() {
        Bus.$emit("click", this._uid);
      },
      className(id) {
        return this.room_classes.where('id', id).name;
      }
    },
    computed: {
      ...mapState('Meta', ['room_classes']),
      ...ReservationCreator.mapProps()
    },
    created() {
      this.$on('module_activated', () => {
        this.$watch('hotel_id', e => {
          this.getRooms();
          this.getPolicy();
        });
        this.$watch('from', this.getRooms);
        this.$watch('till', this.getRooms);
      });

      Bus.$on('click', _uid => {
        if (this._uid !== _uid) this.inputDate = false;
      });
    }
  }
</script>