<template>
  <div class="changes-saved-popup" :style="styles" @transitionend="transitionEnd">
    {{ __('extranet.changes_saved') }}
    <i class="icon-close" @click="hide"></i>
  </div>
</template>

<script>
  import {mapState, mapActions, mapGetters, mapMutations} from 'vuex';

  export default {
    data () {
      return {
        display: 'none',
        visible: false,
      }
    },
    computed: {
      ...mapState('DashboardTable', ['changesSavedPopupVisible']),
      opacity () {
        return this.visible * 1;
      },

      styles () {
        return {
          opacity: this.opacity,
          display: this.display,
        }
      }
    },
    methods: {
      ...mapMutations('DashboardTable', {hide: 'changesSavedPopupHide'}),
      transitionEnd () {
        this.display = this.visible ? 'block' : 'none';
      },
    },
    watch: {
      changesSavedPopupVisible (current, prev) {
        this.display = 'block';
        if (current) {
          setTimeout(() => this.visible = true, 1);
        } else {
          this.visible = false;
        }
      }
    }
  }
</script>  