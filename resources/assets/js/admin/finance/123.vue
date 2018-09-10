<template>
  <div>
    <date-selector></date-selector>

    <div>
      <zaloopa></zaloopa>
      <calendar>
        <categories>
          <category-header>
            {{ category_name }}
            Доступность
            <rates>
              <occupancies>

              </occupancies>
            </rates>
          </category-header>
        </categories>
        <calendar-content>
          <dates-row>
            <date></date>
          </dates-row>
          <category-inner v-for="id in category">
            <closed></closed>
            <availability></availability>
            <rates-inner>
              <prices>
                <price></price>
              </prices>
              <minstay></minstay>
            </rates-inner>
          </category-inner>
        </calendar-content>
      </calendar>
    </div>

  </div>
</template>

<script>
  import {mapState, mapActions, mapGetters, mapMutations} from 'vuex';

  export default {}
</script>  