<template>
  <div class='map'>
    <div class='map-instance'></div>
    <div style='display:none;'>
      <g-marker v-for='hotel in hotelsReverse' :hotel='hotel'></g-marker>
    </div>
    <div class='toggle-map-influence' v-if='!simple'>
      <label>
        <input type='checkbox' v-model='influencesFilters'/>
        {{ __('search.search_by_map') }}
      </label>
    </div>
  </div>
</template>

<script>
  import {options, appearance} from './options';
  import GMarker from './GMarker.vue';
  import {mapMutations} from 'vuex';

  export default {
    data () {
      return {
        map: null,
        markers: [],
        loaded: false
      }
    },
    components: {
      GMarker
    },
    props: {
      hotels: Array,
      center: Object,
      simple: Boolean
    },
    methods: {
      fitBounds () {
        if (this.influencesFilters) return;

        if ((this.SWPoint.init || this.NEPoint.init) && !this.simple) {
          const lat = this.$store.getters.cityData.lat;
          const lng = this.$store.getters.cityData.lng;

          this.map.setCenter(new google.maps.LatLng(lat, lng));
          this.map.setZoom(11);
          return;
        }

        if (this.SWPoint.lat === this.NEPoint.lat || this.SWPoint.lng === this.NEPoint.lng) {
          this.map.setCenter(new google.maps.LatLng((Number(this.SWPoint.lat) + Number(this.NEPoint.lat)) / 2, (Number(this.SWPoint.lng) + Number(this.NEPoint.lng)) / 2));
          this.map.setZoom(14);
          return;
        }

        let bounds = new google.maps.LatLngBounds();
        if (!this.simple) {
          bounds.extend(new google.maps.LatLng(this.SWPoint.lat, this.SWPoint.lng));
          bounds.extend(new google.maps.LatLng(this.NEPoint.lat, this.NEPoint.lng));
        } else {
          bounds.extend(new google.maps.LatLng(this.hotels[0].lat - 1, this.hotels[0].lng - 1));
          bounds.extend(new google.maps.LatLng(this.hotels[0].lat + 1, this.hotels[0].lng + 1));
        }
        this.map.fitBounds(bounds);
      },
      idle() {
        this.$emit('load');
        this.loaded = true;
        if (!this.influencesFilters) return;
        const bounds = this.map.getBounds();
        this.setMap({
          southWest: {
            lat: bounds.getSouthWest().lat(),
            lng: bounds.getSouthWest().lng()
          },
          northEast: {
            lat: bounds.getNorthEast().lat(),
            lng: bounds.getNorthEast().lng()
          },
          zoom: this.map.getZoom()
        });
      },
      ...mapMutations('Filters', ['setMap', 'unsetMap']),
      fitBoundsInit() {
        const params = this.$store.state.Filters.map;
        let bounds = new google.maps.LatLngBounds();
        bounds.extend(new google.maps.LatLng(params.southwest.lat, params.southwest.lng));
        bounds.extend(new google.maps.LatLng(params.northeast.lat, params.northeast.lng));
        this.map.fitBounds(bounds);
        setTimeout(() => this.map.setZoom(params.zoom), 100);
      }
    },
    mounted () {
      options.center = new google.maps.LatLng(this.center.lat, this.center.lng);
      window.map = this.map = new google.maps.Map(this.$el.querySelector('.map-instance'), options);

      this.map.addListener('idle', this.idle);

      this.map.setOptions({styles: appearance, clickableIcons: false});
      
      if (this.$store.state.Filters && this.$store.state.Filters.map_active) this.fitBoundsInit();

      if (this.simple) this.$once('load', () => this.fitBounds());
    },

    computed: {
      hotelsReverse() {
        let h = this.hotels.map(h => h);
        h = h.reverse();

        return h;
      },
      SWPoint () {
        return this.hotels.reduce(function (aggr, hotel) {
          if (Number(hotel.lat) < aggr.lat) {
            aggr.lat = hotel.lat;
            aggr.init = false;
          }

          if (Number(hotel.lng) < aggr.lng) {
            aggr.lng = hotel.lng;
            aggr.init = false;
          }

          return aggr;
        }, {lat: 1000, lng: 1000, init: true});
      },
      NEPoint () {
        return this.hotels.reduce(function (aggr, hotel) {
          if (Number(hotel.lat) > aggr.lat) {
            aggr.lat = hotel.lat;
            aggr.init = false;
          }

          if (Number(hotel.lng) > aggr.lng) {
            aggr.lng = hotel.lng;
            aggr.init = false;
          }

          return aggr;
        }, {lat: 0, lng: 0, init: true});
      },
      influencesFilters: {
        get() {
          const filters = this.$store.state.Filters;
          return filters ? filters.map_active : false;
        },
        set(value) {
          const filters = this.$store.state.Filters;
          if (filters) {
            value ? this.$store.commit('Filters/activateMap') : this.$store.commit('Filters/deactivateMap');
          }
        }
      }
    },
    watch: {
      hotels: {
        handler (value) {
          this.fitBounds();
        },
        deep: true
      },
      influencesFilters(val) {
        if (!val) {
          this.unsetMap();
        } else {
          this.idle();
        }
      },
      center (center) {
        if (this.influencesFilters)
          this.map.setCenter(new google.maps.LatLng(center.lat, center.lng));
      }
    }
  }
</script>

<style scoped lang='scss'>
  .map-instance {
    width: 100%;
    height: 100%;
  }
  
  .toggle-map-influence {
    position: absolute;
    top: 10px;
    left: 50px;
    background: white;
    border-radius: 3px;
    padding: 3px 7px 3px 7px;
    box-shadow: rgba(0, 0, 0, 0.298039) 0px 1px 4px -1px;
    
    label {
      display: flex;
      align-items: center;
      align-content: center;
      justify-content: center;
      line-height: 20px;
      
      input {
        margin: 0 3px 0 0;
      }
    }
  }
</style>