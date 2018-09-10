<template>
  <div class='card promo_code' v-if='module_active && (id || !ID)'>
    <h2>Промо-код {{ code || id }}</h2>
    
    <div>
      <label>
        <span>Код</span> <button class='generate' @click='generate'>Сгенерировать</button>
        <input v-model='code'/>
      </label>
      <label>
        <span>Скидка</span>
        <input v-model='discountValue' type='number'/>
        <select v-model='discountType'>
          <option>%</option>
          <option>RUB</option>
        </select>
      </label>
      <label>
        <span>Одноразовый</span>
        <input type='checkbox' @change='e => setOneTime(e.target.checked)'
               :checked='is_used === false || is_used === true'/>
      </label>
      <div class='dates'>
        <span>Начало и окончание действия</span>
        <date-filter :from_prop="from" :till_prop="till" @input="setDates"></date-filter>
      </div>
      <div class='conditions' v-if='sources.cities && sources.hotels'>
        <span>Условия</span>
        <table class='bare' cellspacing='0' cellpadding='0'>
          <tr>
            <th></th>
            <th>Условие</th>
            <th>Список</th>
          </tr>
          <tr>
            <td>Город</td>
            <td>
              <select :value='citiesInclude' @input='e => setInclude({type: "cities", value: !!Number(e.target.value)})'>
                <option value='1'>Включить</option>
                <option value='0'>Исключить</option>
              </select>
            </td>
            <td>
              <button @click='showOverlay("cities")'><i class='fa fa-edit'></i></button>
              <div class='list'>
                <span v-for='city in cityList'>{{city.name}}</span>
                <span v-if='cityList.length === 0'>Все</span>
              </div>
            </td>
          </tr>
          <tr>
            <td>Отель</td>
            <td>
              <select :value='hotelsInclude' @input='e => setInclude({type: "hotels", value: !!Number(e.target.value)})'>
                <option :value='1'>Включить</option>
                <option :value='0'>Исключить</option>
              </select>
            </td>
            <td>
              <button @click='showOverlay("hotels")'><i class='fa fa-edit'></i></button>
              <div class='list'>
                <span v-for='hotel in hotelList'>{{hotel.name}}</span>
                <span v-if='hotelList.length === 0'>Все</span>
              </div>
            </td>
          </tr>
        </table>
        
        <div class='admin-overlay' v-if='adminOverlaySource' @click='closeAdminOverlay'>
          <select-list @input='toggleRow' :rows='adminOverlaySource' :selected='selectedFilter'></select-list>
        </div>
      </div>
    </div>
    
    <div class='activity' v-if='id'>
      <h3>Статус</h3>
      <template v-if='!deactivated'>
        <span>Активен</span>
        <button @click='deactivate'>Деактивировать</button>
      </template>
      <template v-else>
        <span>Неактивен</span>
        <button @click='activate'>Активировать</button>
      </template>
      <button class='delete' @click='del'>Удалить</button>
    </div>
    
    <button :class="['apply', promoCodeSaveStatus]" @click='e => set()'>
      {{ promoCodeButtonString }}
    </button>
  
    <button v-if='need_approval' @click='setForce' class='force'>
      Сохранить принудительно
    </button>
  </div>
</template>

<script>
  import DateFilter from '../components/DateFilter.vue';
  import SelectList from '../components/SelectList.vue';
  import PromoCode from '../store/modules/PromoCode';

  export default {
    module: {PromoCode},
    components: {DateFilter, SelectList},
    props: ['promo_code_id'],
    computed: {
      ...PromoCode.mapProps(),
      discountType: {
        get() {
          return this.discountTypeValue[1];
        },
        set(value) {
          this.discount = this.discountValue + ' ' + value;
        }
      },
      discountValue: {
        get() {
          return this.discountTypeValue[0];
        },
        set(value) {
          this.discount = value + ' ' + this.discountType;
        }
      },
      cityList() {
        if (!this.filter.cities) return [];
        return this.filter.cities.ids.map(city => this.sources.cities.find(c => city === c.id));
      },
      hotelList() {
        if (!this.filter.hotels) return [];
        return this.filter.hotels.ids.map(hotel => this.sources.hotels.find(h => hotel === h.id));
      },
      selectedFilter() {
        if (!this.filter[this.adminOverlayType]) {
          return [];
        }
        return this.filter[this.adminOverlayType].ids;
      },
      citiesInclude() {
        return this.filter.cities ? Number(this.filter.cities.include) : 1
      },
      hotelsInclude() {
        return this.filter.hotels ? Number(this.filter.hotels.include) : 1
      }
    },
    methods: {
      setForce() {
        this.set(true);
      },
      generate() {
        const randLetter = () => (10 + Math.floor(Math.random() * 26 % 26)).toString(36).toUpperCase();
        const randNumber = () => Math.floor(Math.random() * 10 % 10);
        
        this.code = randLetter() + randLetter() + randLetter() + randNumber() + randNumber() + randNumber();
      },
      initModule() {
        this.setID(this.promo_code_id);
        this.loadSource('hotels');
        this.loadSource('cities');
        
        if (this.promo_code_id) {
          this.load();
        }
      },
      showOverlay(type) {
        this.adminOverlaySource = this.sources[type];

        this.adminOverlayType = type;
      },
      closeAdminOverlay() {
        this.adminOverlaySource = false;
      },
      ...PromoCode.mapMethods()
    }
  }
</script>