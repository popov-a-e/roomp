<template>
  <div class='sub-gallery-container' v-on:click.stop=''>
    <div class='sub-gallery' :style='{width: Math.min(170 * (photosVisible.length - 1) + 150, 660) + "px"}'>
      <button class='prev' v-on:click.stop='shiftPrev'><i class='icon-chevron-left'></i></button>
      <div class='gallery-photo-overflow'>
        <transition-group name='gallery-photo' tag='div' class='gallery-photo-container'  :class='direction'>
          <div v-on:click.stop=''
               v-for='(photo,i) in photosVisible'
               v-bind:style='{backgroundImage: `url(${image_url(photo.value.src)})`}'
               v-bind:key='photo.id'
               v-bind:class='{selected: selected === photo.i}'
               v-on:click='select(photo.i, i)'
          ></div>
        </transition-group>
      </div>
      <button class='next' v-on:click.stop='shiftNext'><i class='icon-chevron-right'></i></button>
    </div>
  </div>
</template>

<script>
  import {mapState, mapActions, mapMutations, mapGetters} from 'vuex';

  export default {
    data () {
      return {}
    },
    computed: {
      ...mapState('Gallery', ['direction', 'selected']),
      ...mapGetters('Gallery', ['photosVisible'])
    },
    methods: {
      select (id, index) {
        this.selectPhoto(id);
        
        if (index === 0) {
          this.shiftPrev();
        }
        
        if (index === 3) {
          this.shiftNext();
        }
      },
      ...mapMutations('Gallery', ['shiftNext', 'shiftPrev', 'selectPhoto'])
    }
  }
</script>

<style scoped lang='scss'>
  $img-width: 150px;
  
  .sub-gallery > div {
    div > div {
      float: left;
      display: block;
      transition: all 0.3s;
      width: $img-width;
      position: relative;
      cursor: pointer;
      
      &:before {
        content: " ";
        background: rgba(0, 0, 0, 0.6);
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
        bottom: 0;
      }
      
      &.selected {
        &:before {
          content: none;
        }
      }
    }
  }
  
  .gallery-photo-container.next {
    .gallery-photo-leave {
      margin-left: 0;
    }
    
    .gallery-photo-leave-to {
      margin-left: -($img-width + 20px);
    }
  }
  
  .gallery-photo-container.prev {
    .gallery-photo-enter {
      margin-left: -($img-width + 20px);
    }
    
    .gallery-photo-enter-to {
      margin-left: 0;
    }
  }

</style>