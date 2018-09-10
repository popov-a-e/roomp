<template>
  <div class='gallery-overlay' v-on:click='toggleOverlay'>
    <div class='gallery-overlay-inner'>
      <div class='main-gallery-view'>
        <div class='gallery-overlay-image'>
          <div class='image-container' v-bind:style='{marginRight: rooms ? "20px" : "0"}'>
            <button class='prev' v-on:click.stop='prevPhoto'><i class='icon-chevron-left'></i></button>
            <button class='next' v-on:click.stop='nextPhoto'><i class='icon-chevron-right'></i></button>
            <div class='image' v-on:click.stop='nextPhoto'
                 v-bind:style='{backgroundImage: `url(${image_url(photoSelected)})`}'>
            </div>
          </div>
          <div v-on:click.stop='' v-if='rooms' class='description'>
            <h2>{{ __('hotel.room_name', {name: roomClass}) }}</h2>
            <div v-if='roomSize > 0'>
              <h3>{{ __('hotel.room_size') }}</h3>
              <span>{{ roomSize }} {{ __('hotel.m') }}<sup>2</sup></span>
            </div>
            <div>
              <h3>{{ __('hotel.amenities') }}</h3>
              <span v-for='amenity in amenities'>{{amenity}}</span>
            </div>
            <div>
              <h3>{{ __('hotel.allotments_available') }}</h3>
              <span v-for='allotment in allotments'>
                {{allotment}}
              </span>
            </div>
          </div>
        </div>
      </div>
      <sub-gallery></sub-gallery>
    </div>
  </div>
</template>

<script>
  import SubGallery from './SubGallery.vue';
  import {mapState, mapActions, mapMutations, mapGetters} from 'vuex';
  import Bus from './../../../Bus';

  export default {
    components: {
      SubGallery
    },
    data () {
      return {}
    },
    computed: {
      ...mapGetters('Gallery', ['photoSelected', 'amenities', 'allotments', 'roomSize', 'roomClass']),
      ...mapState('Gallery', ['rooms'])
    },
    methods: {
      ...mapMutations('Appearance', ['toggleOverlay']),
      ...mapMutations('Gallery', ['nextPhoto', 'prevPhoto'])
    },
    created () {
      if (this.$store.state.Gallery.loaded) return;
      Bus.$on('keydown',e => {
        if (e.key) {
          if (e.key === 'ArrowLeft') this.prevPhoto();
          if (e.key === 'ArrowRight') this.nextPhoto();
        } else {
          if (e.which === 37) this.prevPhoto();
          if (e.which === 39) this.nextPhoto();
        }
      });
      this.$store.commit('Gallery/load');
    }
  }
</script>