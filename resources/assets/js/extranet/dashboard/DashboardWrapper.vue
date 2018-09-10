<template>
  <div class="dashboard-wrapper">
    <div class="categories-wrapper">
      <categories>

      </categories>
    </div>

    <div class="dashboard-wrapper-content" @scroll="scrollChange" :style="{maxHeight: dashboardIsVerticallyScrollable > 0 ? `calc(100vh - ${305 - dashboardIsVerticallyScrollable}px)` : null}">
      <div class="dashboard-content" :style="{width: (11 + dates.length * 52) + 'px'}">
        <category-inner
          v-for="(room, roomID) in rooms"
          :roomID="roomID"
        >
        </category-inner>
      </div>
    </div>
  </div>
</template>


<script>
  import Categories from "./DashboardTable/Categories.vue";
  import CategoryInner from "./DashboardTable/CategoryInner";
  import Bus from '../../Bus';

  import {mapState, mapGetters, mapMutations} from "vuex";

  export default {
    components: {
      Categories, CategoryInner
    },
    data() {
      return {
        console,
        scrollActive: false
      }
    },
    computed: {
      ...mapState('DashboardEditor', ['rangeStart', 'rangeEnd', 'from', 'till']),
      ...mapState('DashboardTable', ['rooms', 'scrollTop', 'dashboardIsVerticallyScrollable']),
      ...mapState('SelectionWindow', ['inRightZone', 'inLeftZone']),
      ...mapGetters('DashboardTable', ['dates', 'state'])
    },
    methods: {
      ...mapMutations('DashboardEditor', ['setRangeStart', 'setRangeEnd']),
      ...mapMutations('SelectionWindow', ['rightZoneEnter', 'rightZoneLeave', 'leftZoneEnter', 'leftZoneLeave']),
      ...mapMutations('DashboardTable', ['scrollChange', 'updateScrollState']),
      scrollRightLoop() {
        let el = this.$el.querySelector('.dashboard-wrapper-content');

        el.scrollLeft = el.scrollLeft + this.inRightZone;

        if (this.inRightZone) {
          setTimeout(this.scrollRightLoop, 100);
        }
      },
      scrollLeftLoop() {
        let el = this.$el.querySelector('.dashboard-wrapper-content');

        el.scrollLeft = el.scrollLeft - this.inLeftZone;

        if (this.inLeftZone) {
          setTimeout(this.scrollLeftLoop, 100);
        }
      },
      ...mapMutations('DashboardEditor', {
        showEditor: 'show'
      }),
    },
    watch: {
      scrollTop: function(value) {
        const el = this.$el.querySelector('.categories-wrapper');

        el.scrollTop = value;
      },
      inLeftZone (current, prev) {
        if (!prev && current) this.scrollLeftLoop();
      //  if (prev && !current) this.leftZoneLeave()
      },
      inRightZone (current, prev) {
        if (!prev && current) this.scrollRightLoop();
      //  if (prev && !current) this.rightZoneLeave()
      },
      state () {
        this.updateScrollState();
      }
    },
    mounted (){
      let el = this.$el.querySelector('.dashboard-wrapper-content');

      Bus.$on('resize', () => this.updateScrollState());

      Bus.$on('mousemove', (x,y,e) => {
        const rect = el.getBoundingClientRect();
        const boundRadius = 20;

        if (x > rect.right - boundRadius && this.rangeStart) {
          this.rightZoneEnter(x - rect.right + boundRadius);
        } else {
          this.rightZoneLeave()
        }

        if (x < rect.left + boundRadius && this.rangeStart) {
          this.leftZoneEnter(rect.left + boundRadius - x);
        } else {
          this.leftZoneLeave();
        }
      });

      Bus.$on('mousemove', (x,y,e) => {
        const button = e.buttons || e.which;

        if (button !== 1) {
          if (this.from && this.till) {
            this.showEditor();
          } else {
            this.setRangeStart(null);
            this.rightZoneLeave();
            this.leftZoneLeave();
          }
        }
      })
    }
  }
</script>