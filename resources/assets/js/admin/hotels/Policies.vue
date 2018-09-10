<template>
  <div
    class='card policies'
    v-if='module_active'
  >
    <h2>Политики в отеле</h2>
    
    <div>
      <label class='beds'>
        <input type='number' v-model='bed_number'/>
        <span>Число дополнительных спальных мест в номерах</span>
      </label>
      <template v-if='bed_number > 0'>
        <h3>Дополнительное место могут занимать:</h3>
        <label class='check'>
          <input type='checkbox' v-model='infantsActive'/>
          <span>Грудные младенцы(до 2 лет)</span>
        </label>
        <label class='price' v-if='infantsActive'>
          <span>Цена</span>
          <input v-model='price_infants'/>
        </label>
        <label class='check'>
          <input type='checkbox' v-model='childrenActive'/>
          <span>Дети</span>
        </label>
        <label class='price' v-if='childrenActive'>
          <span>Цена</span>
          <input v-model='price_children'/>
        </label>
        <label class='age' v-if='childrenActive'>
          <span>Возраст</span>
          <input type='number' v-model='age_children'/>
        </label>
        <label class='check'>
          <input type='checkbox' v-model='adultsActive'/>
          <span>Взрослые</span>
        </label>
        <label class='price' v-if='adultsActive'>
          <span>Цена</span>
          <input v-model='price_adults'/>
        </label>
      </template>
      <label class='beds'>
        <input type='number' v-model='breakfast_price'/>
        <span>Стоимость завтрака</span>
      </label>
    </div>

    <div class="last_minute">
      <p>Правила Last minute</p>

      <table>
        <tr>
          <th>
            Часов до окончания даты заезда
          </th>
          <th>
            Скидка
          </th>
        </tr>
        <tr v-for="(discount, hour) in last_minute">
          <td>
            <input
              type="number"
              :min="limits[hour].hours.min"
              :max="limits[hour].hours.max"
              :value="hour"
              @input="e => updateLastMinuteRule(e.target.value, discount, hour)"
            />
            <span> ч.</span>
          </td>
          <td>
            <input
              type="number"
              :min="limits[hour].discount.min"
              :max="limits[hour].discount.max"
              :value="discount"
              @input="e => updateDiscount(hour, e.target.value)" />
            <span>%</span>
          </td>
          <td>
            <button @click="removeRoom(hour)">-</button>
          </td>
        </tr>
        <tr>
          <td colspan="3">
            <button @click="addRule({hour: minHour, discount: maxDiscount})">+ Добавить правило</button>
          </td>
        </tr>
      </table>
    </div>

    <button @click='save' :class='loading_state === "ready" ? "" : "busy"'>{{loadingState}}</button>
  </div>
</template>

<script>
  import CButton from '../../components/Cbutton.vue';
  import Policies from '../store/modules/card/Policies';
  import Bus from './../../Bus';
  
  export default {
    module: {Policies},
    props: ['hotel_id'],
    computed: {
      ...Policies.mapProps(),
      infantsActive: {
        get () {
          return this.price_infants !== null;
        },
        set (value) {
          this.setPriceInfants(value ? 500 : null);
        }
      },
      childrenActive: {
        get () {
          return this.price_children !== null;
        },
        set (value) {
          this.setPriceChildren(value ? 500 : null);
        }
      },
      adultsActive: {
        get () {
          return this.price_adults !== null;
        },
        set (value) {
          this.setPriceAdults(value ? 500 : null);
        }
      }
    },
    methods: {
      ...Policies.mapMethods(),
      updateDiscount(hour, discount) {
        if (discount < this.limits[hour].discount.min || discount > this.limits[hour].discount.max) return;

        this.setLastMinuteRule({discount, hour});
      },
      updateLastMinuteRule(hour, discount, prev) {
        if (hour < this.limits[prev].hours.min || hour > this.limits[prev].hours.max) return;
        if (this.last_minute[hour]) return;

        this.setLastMinuteRule({discount, hour, prev});
      },
      initModule () {
        this.setHotelID(this.hotel_id);
        this.load();
      }
    },
  }
</script>