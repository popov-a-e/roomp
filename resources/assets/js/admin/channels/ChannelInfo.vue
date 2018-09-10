<template>
  <div class='channels card' @click.prevent.stop=''>
    <button v-if='!room' @click='refresh'><span v-if='loading'>Загрузка</span><span v-else>Обновить информацию с OTA</span></button>
    
    <table cellspacing='0' cellpadding='0'>
      <tr>
        <th style='width:100px; min-width: 100px; max-width: 100px;'><filter-text :val='filters.id' :name='"id"' :sortBy='sortBy' :placeholder='"#"' v-on:input='setIDFilter' v-on:sort='setSort'></filter-text></th>
        <th><filter-text :val='filters.name' :name='"name"' :sortBy='sortBy' :placeholder='"Название"' v-on:input='setNameFilter'  v-on:sort='setSort'></filter-text></th>
        <th v-if='room'><filter-text :val='filters.room_id' :name='"room_id"' :sortBy='sortBy' :placeholder='"Ассоциирован ID"' v-on:input='setRoomIDFilter'  v-on:sort='setSort'></filter-text></th>
        <th v-else><filter-text :val='filters.hotel_id' :name='"hotel_id"' :sortBy='sortBy' :placeholder='"Ассоциирован ID"' v-on:input='setHotelIDFilter'  v-on:sort='setSort'></filter-text></th>
      </tr>
      <tr @click='rowClick(row)' v-for='row in rows'>
        <td>{{ row.id }}</td>
        <td>{{ row.name }}</td>
        <td v-if='room'>{{row.room_id}}</td>
        <td v-else>{{row.hotel_id}}</td>
      </tr>
    </table>
  </div>
</template>

<script>
  import { mapState, mapActions, mapMutations, mapGetters } from 'vuex';
  import FilterText from './../components/Filters/FilterText.vue';

  export default {
    components: {
      FilterText
    },
    data () {
      return {}
    },
    props: ['channel', 'hotel', 'room', 'hotel-roomp'],
    computed: {
      ...mapState('ChannelInfo', ['loading', 'filters', 'sortBy']),
      ...mapGetters('ChannelInfo', {rows: 'rowsFiltered'})
    },
    methods: {
      ...mapMutations('ChannelInfo', ['flush', 'setChannel', 'setHotel', 'setRoompHotel', 'setRoom', 'setIDFilter', 'setNameFilter', 'setHotelIDFilter', 'setRoomIDFilter', 'setSort']),
      ...mapActions('ChannelInfo', ['load', 'set', 'refresh']),
      init() {
        this.flush();
        this.setChannel(this.channel);
        this.setHotel(this.hotel);
        this.setRoompHotel(this.hotelRoomp);
        this.setRoom(this.room);
        this.load();
      },
      rowClick(row) {
        this.set(row.id).then(() => {
          this.$emit('input');
        });
      }
    },
    created () {
      this.init();
    }
  }
</script>