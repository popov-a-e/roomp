<template>
  <div class="price">
    <h2>{{__('search.price')}}</h2>
    <div class="slider">
      <div class="bg-stripe"></div>
      <div
        class="stripe"
        v-bind:style="{ left: currentPosition.left + '%', width: (100 + currentPosition.right - currentPosition.left) + '%' }"
      ></div>
      <div
        class="handler left"
        @mousedown.prevent="start"
        @touchstart.prevent='start'
        @mousemove.prevent=""
        @touchmove.prevent=""
        @click.prevent='' @touch.prevent=''
        :class='{active: side==="left"}'
        data-side="left"
        v-bind:style="{ left: currentPosition.left + '%'}"
      ></div>
      <div
        class="handler right"
        v-on:mousedown.prevent="start"
        @touchstart.prevent='start'
        v-on:mousemove.prevent=""
        @touchmove.prevent=""
        :class='{active: side==="right"}'
        v-bind:style="{ left: currentPosition.right + '%'}"
        data-side="right"
      ></div>
      <div class="price_min">{{ price.min }}</div>
      <div class="price_max">{{ price.max < maxPrice + 1 ? price.max : maxPrice + '+' }}</div>
    </div>
  </div>
</template>

<script>
  import Bus from '../../../Bus';
  import {mapMutations, mapState} from 'vuex';

  export default {
    data () {
      return {
        active: false,
        width: 0,
        side: '',
        x: 0,
        y: 0,
        initialPosition: {
          left: 0,
          right: 0
        },
        currentPosition: {
          left: 0,
          right: 0
        },
        price: {
          min: 0,
          max: 0
        }
      }
    },
    computed: {
      ...mapState({
        priceMin: state => state.Filters.price.min,
        priceMax: state => state.Filters.price.max,
        maxPrice: state => state.Filters.maxPrice,
        multiplier: state => (state.Filters.maxPrice + 1) / 10000
      })
    },
    methods: {
      start (e) {
        this.side = e.target.dataset.side;
        this.active = e.target;
        this.width = this.$el.querySelector('.slider').clientWidth;
        if (window.TouchEvent && e instanceof window.TouchEvent) {
          this.x = e.touches[0].pageX;
          this.y = e.touches[0].pageY;
        } else {
          this.x = e.pageX;
          this.y = e.pageY;
        }
      },
      updatePosition(x) {
        this.currentPosition[this.side] = Math.min(100, (this.initialPosition[this.side] + 100 * (x - this.x) / this.width));

        if (this.side === 'right') {
          this.currentPosition[this.side] = Math.max(-100 + this.initialPosition.left + 1, this.currentPosition[this.side]);
          this.currentPosition[this.side] = Math.min(0, this.currentPosition[this.side]);

          this.price.max = Math.round(this.multiplier * (10000 - Math.abs(this.currentPosition[this.side]) * 100));
        } else {
          this.currentPosition[this.side] = Math.min(100 + this.initialPosition.right - 1, this.currentPosition[this.side]);
          this.currentPosition[this.side] = Math.max(0, this.currentPosition[this.side]);

          this.price.min = Math.round(this.multiplier * Math.abs(this.currentPosition[this.side]) * 100);
        }
      },

      ...mapMutations({
        setPrice: 'Filters/setPrice'
      }),
      updateExternal () {
        this.price.min = this.priceMin;
        this.price.max = this.priceMax;


        this.initialPosition.left = this.currentPosition.left = Math.min(100, this.price.min / this.multiplier / 100);
        this.initialPosition.right = this.currentPosition.right = Math.min(0, (this.price.max - this.maxPrice) / this.multiplier / 100);
      }
    },
    created () {
      Bus.$on('mouseup', () => {
        if (this.active) {
          this.setPrice(this.price);
        }

        this.active = false;
        this.initialPosition[this.side] = this.currentPosition[this.side];
        this.side = '';
      });

      Bus.$on('mousemove', (x, y, e) => {
        if (!this.active) return;
        if (e.buttons === 0 && (e.touches && e.touches.length === 0)) return;
        this.active && this.updatePosition(x, y);
      });

      this.updateExternal();
    },
    watch: {
      priceMin () {
        this.updateExternal();
      },
      priceMax (){
        this.updateExternal();
      }
    }
  }
</script>

<style lang="scss" scoped>
  .price {
    padding-right: 20px;
  }

  .slider {
    width: calc(100% - 200px);
    float: left;
    height: 50px;
    position: relative;
  }

  .slider .bg-stripe {
    width: 100%;
    height: 1px;
    position: absolute;
    top: 15.5px;
    left: 0;
    background: #f0f0f0;
  }

  .slider .stripe {
    width: 100%;
    height: 2px;
    position: absolute;
    top: 15px;
    left: 0;
    background: #304090;
  }

  .slider .handler {
    position: absolute;
    height: 16px;
    width: 16px;
    background: white;
    border: 2px solid #f0f0f0;
    border-radius: 8px;
    cursor: pointer;
    top: 8px;
  }

  .right {
    left: 0;
    margin-left: calc(100% - 8px);
  }

  .left {
    margin-left: -8px;
    left: 0;
  }

  .price_min, .price_max {
    position: absolute;
    top: 30px;
    font-size: 12px;
  }

  .price_min {
    left: 0;
  }

  .price_max {
    right: 0;
  }
</style>