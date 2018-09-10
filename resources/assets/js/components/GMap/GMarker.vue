<template>
  <div class='marker' v-bind:class='{enlighted, soldout}'
       v-on:click.stop.prevent='click'
       v-on:dblclick.stop.prevent=''
  >
    <div class='price' v-if='!open'>
      <div class='inner'>
        <p v-if="typeof hotel.price === 'number'" v-html="toCurrency(hotel.price)"></p>
        <p v-else>{{ hotel.name }}</p>
        <i></i>
      </div>
    </div>
    <div v-if='open' class='info-window'>
      <hotel :hotel='hotel' :from="from" :till="till">

      </hotel>
      <i></i>
    </div>
  </div>
</template>

<script>
  import CustomMarker from './Marker';
  import Hotel from './../Hotel.vue';
  import Bus from './../../Bus';

  export default {
    data () {
      return {
        open: false
      }
    },
    props: ['hotel'],
    components: {
      Hotel
    },
    methods: {
      click () {
        this.open = true;
        Bus.$emit('click', this._uid);
      },
      reposition () {
        try {
          this.markerInstance.reposition(new google.maps.LatLng(this.hotel.lat, this.hotel.lng));
        } catch (e) {
        
        }
      },
      load () {
        if (this.markerInstance) return;
        this.markerInstance = new CustomMarker(
          this.$parent.map,
          new google.maps.LatLng(this.hotel.lat, this.hotel.lng),
          {
            div: this.$el
          },
        );

        this.$watch('hotel', hotel => {
          if (!this.$parent.simple) this.reposition();
        }, {deep: true});

        if (this.markerInstance.getProjection()) {
          this.reposition();
        }
      }
    },
    mounted () {
      if (this.$parent.loaded) {
        this.load();
      } else {
        this.$parent.$once('load', this.load);
      }
      
      Bus.$on('click', (_uid) => {
        if (this._uid !== _uid) this.open = false;
      });
    },
    computed: {
      soldout () {
        return this.hotel.count === 0;
      },
      enlighted () {
        return this.$store.state.Appearance.enlightedHotel === this.hotel.id;
      },
      from () {
        if (this.$store.state.Filters) return this.$store.state.Filters.from;
        return null;
      },
      till () {
        if (this.$store.state.Filters) return this.$store.state.Filters.till;

        return null;
      }
    },
  }
</script>

<style scoped lang='scss'>
  .marker {
    position: absolute;
    height: 20px;
    background: white;
    cursor: pointer;
    width: 0;
    display: flex;
    justify-content: center;
    margin-top: -25px;

    &.soldout {
      > div.price {
        background: #999;

        div.inner {
          background: #999;

          i, p {
            background: #999;
          }
        }
      }
    }

    > div.price {
      color: white;
      margin: 0;

      div.inner {
        position: relative;
        float: left;
        margin-top: 0;
        background: #304090;
        color: #304090;
        box-shadow: 0 0 0 1px rgba(255, 255, 255, 0.2);
        -webkit-box-shadow: 0 0 0 1px rgba(255, 255, 255, 0.2);
        -moz-box-shadow: 0 0 0 1px rgba(255, 255, 255, 0.2);
        padding: 0 6px;
        white-space: nowrap;
        font-size: 12px;
        line-height: 20px;
        z-index: 2;

        i {
          position: absolute;
          bottom: -3px;
          left: calc(50% - 4px);
          width: 8px;
          height: 8px;
          z-index: 1;
          transform: rotate(-45deg);
          background: #304090;
          box-shadow: 0 0 0 1px rgba(255, 255, 255, 0.2);
          -webkit-box-shadow: 0 0 0 1px rgba(255, 255, 255, 0.2);
          -moz-box-shadow: 0 0 0 1px rgba(255, 255, 255, 0.2);
        }

        p {
          position: relative;
          float: left;
          background: #304090;
          color: white;
          width: 100%;
          height: 100%;
          font-size: 12px;
          line-height: 20px;
          z-index: 2;
          margin: 0;
          padding: 0 6px;
          white-space: nowrap;
        }
      }
    }

    &.enlighted, &:hover {
      z-index: 300;
      & > div.price {
        color: #304090;

        div.inner {
          background: white;
          color: white;

          i {
            background: white;
          }

          p {
            background: white;
            color: #304090;
          }
        }
      }
    }

  }

  .info-window {
    width: 300px;
    height: 150px;
    background: white;
    margin-top: -130px;
    padding: 5px;
    z-index: 3;

    .hotel {
      width: 290px;
      height: 140px;
    }

    i {
      position: absolute;
      bottom: -3px;
      left: calc(50% - 4px);
      width: 8px;
      height: 8px;
      z-index: 1;
      transform: rotate(-45deg);
      background: white;
    }
  }

</style>