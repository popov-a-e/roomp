<template>
  <div class='room-nav'>
    <navigation></navigation>
    <category></category>
  </div>
</template>

<script>
  import Category from './Category.vue';
  import Navigation from './Navigation.vue';

  export default {
    components: {
      Category, Navigation
    },
    props: ['photos'],
    created () {
      const photos = this.photos.split(',');
      
      this.$store.commit('initHotelPhotos',photos);
    }
  }
</script>