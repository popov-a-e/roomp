<template>
  <div class="selection-window" :style="styles"></div>
</template>

<script>
  import {mapState, mapMutations, mapActions, mapGetters} from 'vuex';

  export default {
    data() {
      return {
        dashboardEl: null,
        calendarEl: null
      }
    },
    computed: {
      ...mapState('SelectionWindow', ['x1', 'x0', 'y1', 'y0', 'inRightZone']),

      styles() {
        const dashboardEl = this.dashboardEl;
        const calendarEl = this.calendarEl;
        const body = document.documentElement || document.body;
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;

        if (!dashboardEl) return {};
        const calendarSizes = dashboardEl.getBoundingClientRect();
        if (this.x0 === null) return {};

        const top  = Math.max(0, Math.min(this.y0, this.y1) - calendarSizes.top - scrollTop);
        const left = Math.max(0, Math.min(this.x0, this.x1) - calendarSizes.left);

        const yMin = Math.max(calendarSizes.top + scrollTop, Math.min(this.y1, this.y0));
        const yMax = Math.max(this.y1, this.y0);

        const xMin = Math.max(calendarSizes.left, Math.min(this.x1, this.x0));
        const xMax = Math.max(this.x1, this.x0);

        if (calendarSizes.width - (left + xMax - xMin) < 30) {
          this.rightZoneEnter();
          this.scrollLeftLoop();
        } else {
          this.rightZoneLeave();
        }

        return {
          top: top + 'px',
          left: left + 'px',
          height: (yMax - yMin) + 'px',
          width: (xMax - xMin) + 'px'
        }
      },
    },
    methods: {
      ...mapMutations('SelectionWindow', ['rightZoneEnter', 'rightZoneLeave']),
      scrollLeftLoop() {
        this.calendarEl.scrollLeft = this.calendarEl.scrollLeft + 5;

        if (this.inRightZone) {
          setTimeout(this.scrollLeftLoop, 100);
        }
      }
    },
    mounted () {
      this.dashboardEl = document.querySelector('.dashboard');
      window.cl = this.calendarEl = document.querySelector('.dashboard-wrapper');
    }
  }

</script>