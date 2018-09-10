<template>
  <div class='category'>
    <photo-slider></photo-slider>
    <div class='amenities' @wheel='wheel'>
      <span v-for='amenity in amenities'>{{ amenity.name }}</span>
      <div class='scrollbar' :style='{top: scrollTop + "px"}' @mousedown='scrollBarMouseDown' v-if='scrollHeight > 0'></div>
    </div>
  </div>
</template>

<script>
  import PhotoSlider from './PhotoSlider.vue';
  import Bus from '../../../../Bus';

  export default {
    components: {
      PhotoSlider
    },
    data () {
      return {
        amenitiesEl: null,
        scTop: 0,
        scrollBarActive: false,
        y: 0,
        scrollTopFix: 0,
        scrollHeight: 0
      }
    },
    computed: {
      amenities () {
        return this.$store.state.rooms.filter(room => {
          return room.room_class_id === this.$store.state.Appearance.classChecked.id;
        }).reduce((p,c) => {
          let amenities = c.amenities.map(el => el.id);

          return p.concat(amenities);
        },[]).map(amenity => {
          return this.$store.state.BackendData.room_amenities.where('id', amenity);
        });
      },
      scrollTop () {
        if (!this.amenitiesEl) return 0;
        const scrollHeight = this.amenitiesEl.scrollHeight - this.amenitiesEl.offsetHeight;
        
        return this.scTop + (this.scTop / scrollHeight) * (this.amenitiesEl.offsetHeight - 100);
      }
    },
    methods: {
      wheel (e) {
        if (this.scrollHeight > 0) {
          e.preventDefault();
          e.stopPropagation();
        }
        
        const div = this.amenitiesEl;
        
        div.scrollTop = div.scrollTop + e.deltaY;
        this.scTop = div.scrollTop;
      },

      scrollBarMouseDown (e) {
        this.scrollBarActive = true;
        this.y = e.pageY;
        this.scrollTopFix = this.amenitiesEl.scrollTop;
      }
    },
    mounted () {
      this.amenitiesEl = this.$el.querySelector('.amenities');
      
      Bus.$on('mousemove', (x,y,e) => {
        if (e.which !== 1) {
          this.scrollBarActive = false;
          return true;
        }
        
        if (!this.scrollBarActive) return true;
        
        e.preventDefault();
        const div = this.amenitiesEl;
        
        let delta = y - this.y;
        const scrollHeight = div.scrollHeight - div.offsetHeight;

        delta /= (div.offsetHeight - 100) / scrollHeight;
        div.scrollTop = this.scrollTopFix + delta;
        this.scTop = div.scrollTop;
      })
    },
    updated () {
      this.scrollHeight = this.amenitiesEl.scrollHeight - this.amenitiesEl.offsetHeight;
    }
  }
</script>

