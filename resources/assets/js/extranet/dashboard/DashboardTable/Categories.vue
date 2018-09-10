<template>
  <div class="categories">
    <category
      v-for="(roomCategory, roomID) in rooms"
      :roomCategory="roomCategory"
      :roomID="roomID"
    >
    </category>

  </div>
</template>


<script>

  import {mapState} from 'vuex';

  import Category from "./Category.vue";

  export default {
    components: {
      Category
    },
    data() {
      return {}
    },
    computed: {
      ...mapState('DashboardTable', ['rooms']),
    },

    methods: {

    },

    created() {

    },
    mounted() {
    }


  }
</script>