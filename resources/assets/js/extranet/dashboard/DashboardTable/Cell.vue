<template>
  <div
    @mousedown.stop="setStartPoint"
    @mousemove="setEndPoint"
    @click="selectColumn"
    :class="classes">
    {{value === null ? '-' : type === 'text' ? value : ''}}
    <i style="float: left; width: 100%; line-height: 24px; font-weight: bold; color: white;" class="fas fa-asterisk" v-if="this.hasRestrictions"></i>
  
  </div>
</template>

<script>
  import {mapState, mapGetters, mapMutations} from 'vuex';

  export default {
    props: ['rateID', 'roomID', 'date', 'type', 'value', 'hasRestrictions'],
    data() {
      return {}
    },
    computed: {
      classes () {
        let classes = {
          cell: true,
          active: this.highlight,
        };

        if (this.type === 'closed') {
          classes.boolean = true;
          if (this.value === null) {
            classes.undefined = true;
          } else if (typeof this.value === 'boolean') {
            classes.closed = this.value;
          } else {
            classes.closed = false;
            classes[this.value] = true;
          }
        } else {
          classes[this.type] = true;
        }

        return classes;
      },
      ...mapState('DashboardEditor', ['from', 'till', 'rate_id', 'room_id']),

      highlight() {
        return this.from <= this.date && this.till >= this.date && this.room_id === this.roomID && this.rate_id === this.rateID
      },

    },
    methods: {
      ...mapMutations('DashboardEditor', ['setRangeStart', 'setRangeEnd', 'setRoom', 'setRate', 'show']),
      ...mapMutations('DashboardEditor', {
        showEditor: 'show'
      }),

      setStartPoint() {
        this.setRangeStart(this.date);
        this.setRoom(this.roomID);
        this.setRate(this.rateID);
      },

      setEndPoint(e) {
        const button = e.buttons || e.which;
        
        if (button === 1) {
          this.setRangeEnd(this.date);
        }
      },

      selectColumn() {
        this.setRangeStart(this.date);
        this.setRangeEnd(this.date);
        this.setRate(this.rateID);
        this.showEditor();
      }
    },

  }
</script>