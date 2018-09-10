<template>
  <div class='photo-slider'>
    <transition-group name='slider' v-bind:class='direction'>
      <a
        v-for='(photo,i) in photosShown'
        v-bind:key='photo.index'
        v-bind:class='`th${i}`'
        v-on:click='photoClick(i)'
        v-on:transitionends=''
      >
        <div
          v-bind:style='{
            backgroundImage: `url(${image_url(photo.link)})`
          }'
        ></div>
      </a>
    </transition-group>
  </div>
</template>

<script>
  import {mapState, mapMutations} from 'vuex';

  export default {
    data () {
      return {
        direction: 'prev',
        photoChecked: 1
      }
    },
    methods: {
      ...mapMutations('Gallery', ['setRooms', 'setPhotos']),
      ...mapMutations(['galleryOpen']),
      photoClick(index) {
        if (index === 0) {
          this.direction = 'prev';
          this.photoChecked = this.photoChecked === 0 ? this.photos.length - 1 : this.photoChecked - 1;
        }
        if (index === 2) {
          this.direction = 'next';
          this.photoChecked = this.photoChecked === this.photos.length - 1 ? 0 : this.photoChecked + 1;
        }
        if (index === 1) {
          this.galleryOpen();
          this.setRooms(this.roomsFiltered);
          this.setPhotos(this.photos.map(p => {
            return p.link;
          }));
        }
      }
    },
    computed: {
      ...mapState({
        classChecked: state => state.Appearance.classChecked,
        rooms: state => state.rooms
      }),
      roomsFiltered() {
        return this.rooms.filter(room => {
          return room.room_class_id === this.classChecked.id;
        });
      },
      photos () {
        return this.roomsFiltered.reduce(function (p, c) {
          return p.concat(c.photos.split(','));
        }, []).map(function (photo, i) {
          return {
            link: photo,
            index: i
          }
        });
      },
      photosShown () {
        if (this.photos.length < 3) return [];

        let arr = [];
        for (let i = this.photoChecked - 1; i <= this.photoChecked + 1; ++i) {
          let mod = i;
          if (i < 0) mod = this.photos.length + mod;
          arr.push(this.photos[mod % this.photos.length]);
        }

        return arr;
      }
    }
  }
</script>

<style lang='scss' scoped>
  a {
    cursor: pointer;

    &:before {
      content: " ";
      position: absolute;
      top: 10%;
      left: 0;
      width: 100%;
      height: 80%;

      box-shadow: 0 0 30px 0 rgba(0, 0, 0, 1);
      transition: none;
    }

    &.th0 {
      height: 150px;
      width: 240px;
      top: 19px;
      left: -185px;
      z-index: 49;
    }

    &.th1 {
      width: 300px;
      height: 188px;
      top: 0;
      left: 55px;
      z-index: 50;
    }

    &.th2 {
      top: 19px;
      width: 240px;
      left: 355px;
      height: 150px;
      z-index: 49;
    }
  }

  .next {
    .slider-leave-to {
      left: -185px - 240px;
    }

    .slider-enter {
      left: 355px + 240px;
    }
  }

  .prev {
    .slider-enter {
      left: -185px - 240px;
    }

    .slider-leave-to {
      left: 355px + 240px;
    }
  }
</style>