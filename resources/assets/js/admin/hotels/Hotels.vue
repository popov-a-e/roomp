<template>
  <div class='card hotels' v-if='module_active && hotels'>
    <h2>Отели</h2>

    <div class='selector'>
      <button @click="setStageFilter('all')" :class="{selected: stage === 'all'}">
        Все ({{ hotelsByCategory.all.length }})
      </button>
      <button @click="setStageFilter('active')" :class="{selected: stage === 'active'}">
        Активные ({{ hotelsByCategory.active.length }})
      </button>
      <button @click="setStageFilter('signed')" :class="{selected: stage === 'signed'}">
        Подписаны ({{ hotelsByCategory.signed.length }})
      </button>
      <button @click="setStageFilter('signing')" :class="{selected: stage === 'signing'}">
        Готовы работать ({{ hotelsByCategory.signing.length }})
      </button>
      <button @click="setStageFilter('deleted')" :class="{selected: stage === 'deleted'}">
        Отключенные ({{ hotelsByCategory.deleted.length }})
      </button><!--
      <button @click="setStageFilter('initial')" :class="{selected: stage === 'initial'}">
        Ждут очереди ({{ hotelsByCategory.initial.length }})
      </button>-->
    </div>
    <a href="#/hotels/new">Создать новый</a>
    <table cellspacing='0' cellpadding='0'>
      <tr>
        <th style='width:100px; min-width: 100px; max-width: 100px;'><filter-text :val='filters.id' :name='"id"' :sortBy='sortBy' :placeholder='"#"' v-on:input='setIdFilter' v-on:sort='setSort'></filter-text></th>
        <th><filter-text :val='filters.name' :name='"name"' :sortBy='sortBy' :placeholder='"Название Roomp"' v-on:input='setNameFilter'  v-on:sort='setSort'></filter-text></th>
        <th><filter-text :val='filters.regular_name' :name='"regular_name"' :sortBy='sortBy' :placeholder='"Название"' v-on:input='setRegularNameFilter'  v-on:sort='setSort'></filter-text></th>

        <th>
          <filter-select :multiple='true' :options='cities' :selected='filters.city' :name='"city"' :sortBy='sortBy' :placeholder='"Город"' v-on:input='setCityFilter'  v-on:sort='setSort'></filter-select>
        </th>
        <th><filter-text :val='filters.fund' :name='"fund"' :sortBy='sortBy' :placeholder='"Номеров"' v-on:input='setFundFilter'  v-on:sort='setSort'></filter-text></th>
        <th>
          <filter-select style="padding-left: 0;" :multiple='true' :options='channelManagers' :selected='filters.channel_manager' :name='"channel_manager"' :sortBy='sortBy' :placeholder='"Channel manager"' v-on:input='setChannelManagerFilter'  v-on:sort='setSort'></filter-select>
        </th>
        <th>
          B.com
        </th>
        <th>
          Оферта
        </th>
        <th v-if="stage === 'signed'">Заполнение</th>
        <th>Комментарий</th>
      </tr>
      <tr v-on:click='rowClick(hotel.id)' v-for='hotel in hotelsFiltered'>
        <td style='width:100px; min-width: 100px; max-width: 100px;'>{{ hotel.id }}</td>
        <td><a :href="`#/hotels/${hotel.id}`" @click.stop="">{{ hotel.name }}</a></td>
        <td>{{ hotel.regular_name }}</td>
        <td style='padding-left: 10px;'>{{ hotel.city }}</td>
        <td>{{ hotel.fund }}</td>
        <td><span>{{ hotel.channel_manager }}</span><i v-if="hotel.channel_manager !== 'Нет'" :class="{active: hotel.channel_manager_active, cm: true}"></i ></td>
        <td><i style="margin: 0 10px;" :class="{active: hotel.bcom_active, exists: hotel.bcom_exists, cm: true}"></i ></td>
        <td>{{ hotel.offer_accepted }}</td>
        <td v-if="stage === 'signed'" style="position:relative; padding: 0;">
          <div class="progress-bar" @click.stop="fullnessVisibleHotelID = fullnessVisibleHotelID === hotel.id ? null : hotel.id">
            <div class="background"></div>
            <div class="selected" :style="'width:'+fullness(hotel)+'%'"></div>
            <span>{{fullness(hotel) + '%'}}</span>
            <div class="overlay"></div>
          </div>
          <ul class="popup" v-if="hotel.id === fullnessVisibleHotelID">
            <li v-for="(value, key) in hotel.fullness" :class="{active: value}">{{key}}</li>
          </ul>
        </td>
        <td><p>{{hotel.comment}}</p></td>
      </tr>
      <tr>
        <td colspan="3">Всего:</td>
        <td>{{ hotelsFiltered.length }} отелей</td>
        <td>{{ fund }} номеров</td>
      </tr>
    </table>
  </div>
</template>

<script>
  import Hotels from '../store/modules/Hotels';
  import FilterText from './../components/Filters/FilterText.vue';
  import FilterSelect from './../components/Filters/FilterSelect.vue';
  
  
  export default {
    computed: {
      ...Hotels.mapProps(),
      cities() {
        return this.hotelsUnfiltered.pluck('city').unique();
      },
      channelManagers() {
        return this.hotelsUnfiltered.pluck('channel_manager').unique();
      }
    },
    methods: {
      initModule() {
        this.load();
      },
      ...Hotels.mapMethods(),
      fullness(hotel) {
        return Math.floor(100 * Object.keys(hotel.fullness).filter(i => hotel.fullness[i]).length / Object.keys(hotel.fullness).length);
      }
    },
    components: {
      FilterText, FilterSelect
    },
    module: {Hotels}
  }
</script>