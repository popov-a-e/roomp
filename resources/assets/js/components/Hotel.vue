<template>
  <div class="hotel" v-on:mouseenter='mouseenter' v-on:mouseleave='mouseleave'>
    <a :href="url" class="soldout" v-if="soldout(hotel)" @click.stop=""><span>{{ __('search.soldout') }}</span></a>
    <transition-group class='transition' tag='a' v-bind:href='url' name='slider' v-bind:class='direction'>
      <div
        v-for='photo in photos'
        v-if='photoNum === photo.n'
        v-bind:key='photo.n'
        class='slider'
        v-on:click='click'
        v-bind:style='{backgroundImage: `url(${image_url(photo.code)})`}'>
      </div>
    </transition-group>
    <button v-if="!soldout(hotel)" class='prev' v-on:click='prevPhoto'>
      <i class='icon-chevron-left'></i>
    </button>
    <button v-if="!soldout(hotel)" class='next' v-on:click='nextPhoto'>
      <i class='icon-chevron-right'></i>
    </button>
    <p v-if='typeof hotel.price === "number"' v-html="__('common.price_from') + ' ' + toCurrency(hotel.price)"></p>
    <h4>{{hotel.name}}</h4>
  </div>
</template>

<script>
  import {mapState} from 'vuex';
  import Bus from '../Bus';

  export default {
    data () {
      return {
        photoNum: 0,
        direction: 'next',
        Photos: [],
        dd
      }
    },
    props: ['hotel', 'from', 'till'],
    methods: {
      soldout(hotel) {
        return hotel.hasOwnProperty('count') && hotel.count === 0;
      },
      click () {
        window.location.href = this.url;
      },
      mouseenter () {
        if (this.$store.state.Appearance) {
          this.$store.commit('Appearance/ligntHotel', this.hotel.id);
        }
      },
      mouseleave () {
        if (this.$store.state.Appearance) {
          this.$store.commit('Appearance/unligntHotel', this.hotel.id);
        }
      },
      nextPhoto () {
        this.direction = 'next';
        this.photoNum = (this.photoNum + 1) % this.photos.length;
      },
      prevPhoto () {
        this.direction = 'prev';
        if (this.photoNum === 0) {
          this.photoNum = this.photos.length - 1;
        } else {
          this.photoNum = (this.photoNum - 1) % this.photos.length;
        }
      }
    },
    computed: {
      url () {
        return '/hotel/' + this.hotel.pretty_url + (this.from && this.till ? `?from=${this.from}&till=${this.till}` : '');
      },
      photos() {
        let photos = [];
        if (this.hotel.photos_hub) {
          photos = this.hotel.photos_hub.split(',');
        } else if (this.hotel.photos) {
          photos = this.hotel.photos.split(',');
        }

        return photos.map(function (p, i) {
          return {
            n: i,
            code: p
          }
        });
      }
    },
  }
</script>

<style scoped lang='scss'>
  a {
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;

    &.soldout {
      z-index: 3;

      &:before {
        position: absolute;
        content: ' ';
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        background-color: rgba(255, 255, 255, 0.5);
      }

      span {
        position: absolute;
        right: 0;
        top: 10px;
        display: block;
        background: #304090;
        color: white;
        font-size: 16px;
        padding: 10px 20px;
        line-height: 20px;
        font-weight: 500;

        text-transform: uppercase;
      }
    }
  }
  
  .hotel {
    position: relative;
    padding: 20px;
    overflow: hidden;
    
    &:before {
      position: relative;
      float: left;
      width: 100%;
      height: 100%;
      padding-top: 44%;
      content: ' ';
    }
    
    a div:before {
      position: absolute;
      content: ' ';
      top: 0;
      left: 0;
      bottom: 0;
      right: 0;
      background-color: rgba(0, 0, 0, 0.1);
    }
    
    &:hover {
      button {
        display: flex;
      }
      
      .slider {
        background-size: 105%;
      }
    }
    
  }
  
  .slider {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    padding: 20px;
    background-size: 100%;
    background-position: center center;
    transition: all .2s ease;
  }
  
  p {
    color: white;
    position: absolute;
    top: 0;
    left: 0;
    margin: 20px;
    font-size: 16px;
    line-height: 20px;
    font-weight: 400;
    //text-shadow: 0 0 6px #777777;
  }
  
  h4 {
    color: white;
    position: absolute;
    bottom: 0;
    left: 0;
    margin: 20px;
    font-size: 18px;
    font-weight: 500;
    //text-shadow: 0 0 6px #777777;
  }
  
  button {
    position: absolute;
    top: 0;
    width: 20%;
    height: 100%;
    border: none;
    background: none;
    color: white;
    outline: none !important;
    cursor: pointer;
    padding: 0;
    display: flex;
    z-index: 2;
    -webkit-box-pack: justify;
    -moz-box-pack: justify;
    -ms-flex-pack: justify;
    -webkit-justify-content: space-between;
    justify-content: space-between;
    align-items: center;
    text-align: center;
    
    &.prev {
      left: 0;
      right: auto;
    }
    
    &.next {
      right: 0;
      left: auto;
    }
    
    i {
      width: auto;
      -webkit-flex: 1 0 auto;
      flex: 1 0 auto;
    }
  }
  
  .transition.next {
    .slider-enter {
      margin-left: 100% !important;
    }
    .slider-enter-to {
      margin-left: 0 !important;
    }
    
    .slider-leave {
      margin-left: 0 !important;
    }
    
    .slider-leave-to {
      margin-left: - 100% !important;
    }
  }
  
  .transition.prev {
    .slider-enter {
      margin-left: -100% !important;
    }
    .slider-enter-to {
      margin-left: 0 !important;
    }
    
    .slider-leave {
      margin-left: 0 !important;
    }
    
    .slider-leave-to {
      margin-left: 100% !important;
    }
  }
</style>