<template>
  <div class='logs card' v-if="module_active && logs">
    <h2>История</h2>
    <table class="bare" cellpadding="0" cellspacing="0">
      <tr v-if="logs.length === 0">
        <td colspan="3">История взаимодействий пуста</td>
      </tr>
      <tr v-for="log in logs" class="row">
        <td>{{log.admin}}</td>
        <td>{{Date.ISOParse(log.created_at).toDateTimeString()}}</td>
        <td>{{log.message}}</td>
      </tr>
      <tr>
        <td colspan="3">
          <textarea v-model="message" placeholder="Добавить по Ctrl+Enter" @keydown="send"></textarea>
          <button @click="log">Добавить</button>
        </td>
      </tr>
    </table>
  </div>
</template>

<script>
  import Logs from '../store/modules/Logs';

  Date.ISOParse = function(str) {
    let pieces = str.split(/[^0-9]/);
//for (i=0;i<a.length;i++) { alert(a[i]); }
    return new Date (pieces[0],pieces[1]-1,pieces[2],pieces[3],pieces[4],pieces[5] );
  };

  Date.prototype.toDateTimeString = function() {
    const addZero = n => n > 9 ? n : '0' + n;
    const month = addZero(this.getMonth() + 1);
    const minutes = addZero(this.getMinutes());
    const hours = addZero(this.getHours() + 3);
    const date = addZero(this.getDate());
    return date + '.' + month + '.' + this.getFullYear() + ' ' + hours + ':' + minutes;
  };

  export default {
    props: ['hotel_id'],
    module: {Logs},
    computed: {
      ...Logs.mapProps(),
    },
    methods: {
      ...Logs.mapMethods(),
      initModule() {
        this.setHotelID(this.hotel_id);
        this.load();
      },
      send(e) {
        if (e.ctrlKey && e.key === 'Enter') this.log();
      }
    },
    created() {
    },
    mounted () {
    }
  }
</script>