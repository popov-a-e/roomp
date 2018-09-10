<template>
  <div class="dashboard-page" @mousedown.prevent="" @drag.prevent="">
    <h2>{{__('extranet.calendar.header')}}</h2>

    <dashboard-legend></dashboard-legend>
    
    <date-filter></date-filter>

    <dashboard-table></dashboard-table>

    <changes-saved-popup></changes-saved-popup>
  </div>
</template>

<script>
  import {mapState, mapMutations, mapActions, mapGetters} from 'vuex';
  import Bus from '../../Bus';

  import DateFilter from './DateFilter';
  import DashboardTable from "./DashboardTable";
  import DashboardLegend from './DashboardLegend.vue';
  import ChangesSavedPopup from './ChangesSavedPopup.vue';

  export default {
    components: {
      DateFilter, DashboardTable, DashboardLegend, ChangesSavedPopup
    },
    data() {
      return {}
    },
    computed: {
      ...mapState('DashboardEditor', ['from', 'till']),
      ...mapState('DashboardEditor', {dashboardEditorVisible: 'is_visible'}),
      ...mapState('SelectionWindow', ['x0', 'x1', 'y1', 'y0']),
      ...mapState('Header', ['hotel']),
    },

    methods: {
      ...mapMutations('DashboardEditor', ['setRangeStart', 'setRangeEnd']),
      ...mapMutations('SelectionWindow', ['setX1', 'setX0', 'setY1', 'setY0']),
      ...mapActions('DashboardTable', ['getRooms', 'getRates']),

      mousemove(x, y, e) {
        if (this.dashboardEditorVisible) {
          this.setY0(null);
          this.setX0(null);
        }

        const button = e.buttons || e.which;
        
        if (button === 1 && this.from) {
          if (!this.x0 && !this.y0) {
            this.setY0(y);
            this.setX0(x);
          }

          this.setY1(y);
          this.setX1(x);
        } else if (button === 0) {
          this.setY0(null);
          this.setX0(null);
        }
      },

    },

    created() {
      Bus.$on('mousedown', e => {
        if (this.dashboardEditorVisible) return;

        this.setRangeStart(null);
        this.setRangeEnd(null);
      });

      Bus.$on('mousemove', (x, y, e) => {
        e.preventDefault();
        this.mousemove(x, y, e);
      });

      this.getRooms();
      this.getRates();
    },


  }
</script>