<template>
  <div class='gallery-overlay' @touchstart='touchstart' @touchmove.prevent='touchmove' @touchend='touchend' @click='toggleOverlay' @touch='toggleOverlay' >
    <!--<div class='gallery-overlay-inner'>
      <div class='main-gallery-view'>
        <div class='gallery-overlay-image'>-->
          <div class='image-container' :class='{moving: x}'>
            <img
              v-for='(photo, i) in pics'
              @click.stop='nextPhoto'
              :width='width'
              :src='image_url(photo.src, 500)'
              :style='{marginLeft: i === 0 ? marginLeft : 0}'
            />
          </div>
          <!--
          <div v-on:click.stop='' v-if='rooms' class='description'>
            <h2>Номер {{ roomClass }}</h2>
            <div v-if='roomSize > 0'>
              <h3>Размер номера:</h3>
              <span>{{ roomSize }} м<sup>2</sup></span>
            </div>
            <div>
              <h3>Удобства в номере</h3>
              <span v-for='amenity in amenities'>{{amenity}}</span>
            </div>
            <div>
              <h3>Доступные размещения</h3>
              <span v-for='allotment in allotments'>
                {{allotment}}
              </span>
            </div>
          </div>
          -->
    <!--    </div>
      </div>
    </div>-->
  </div>
</template>

<script>
  import {mapState, mapActions, mapMutations, mapGetters} from 'vuex';
  import Bus from './../../../../Bus';

  export default {
    props: ['photos'],
    data() {
      return {
        width: 0,
        x: false,
        timestamp: 0,
        photoIndex: 0,
        d: 0
      }
    },
    computed: {
      ...mapGetters('Gallery', ['photoSelected', 'amenities', 'allotments', 'roomSize', 'roomClass']),
      ...mapState('Gallery', {
        pics: 'photos'
      }),
      ...mapState('Gallery', ['rooms']),
      marginLeft () {
        return -((this.photoIndex - this.d) * this.width) + "px";
      }
    },
    methods: {
      ...mapMutations('Appearance', ['toggleOverlay']),
      ...mapMutations('Gallery', ['nextPhoto', 'prevPhoto']),
      ...mapMutations(['initHotelPhotos']),

      touchstart (e) {
        this.x = e.touches[0].pageX;
        this.timestamp = e.timeStamp;
      },
      touchmove(e) {
        this.d = (e.touches[0].pageX - this.x) / this.width;
      },
      touchend (e) {
        this.x = false;
        const time = e.timeStamp - this.timestamp;
        
        if (Math.abs(this.d) > Math.log(time) / 30) {
          this.photoIndex = Math.min(Math.max(0, this.photoIndex - Math.sign(this.d)), this.photos.length - 1);
        }
        
        this.d = 0;
      }
    },
    created( ){
      this.initHotelPhotos(this.photos);
    },
    mounted () {
      this.width = window.innerWidth;
      
      window.addEventListener('resize', e => this.width = window.innerWidth);
    }
  }
</script>