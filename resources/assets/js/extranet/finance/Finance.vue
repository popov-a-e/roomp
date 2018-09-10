<template>
  <div class="finance-page">

    <h2>{{__('extranet.finance.header')}}</h2>

    <finance-legend></finance-legend>

    <finance-table></finance-table>

  </div>
</template>



<script>
  import FinanceLegend from './FinanceLegend';
  import {mapState} from 'vuex';
  import FinanceTable from "./FinanceTable";

  export default {
    components: {
      FinanceTable,
      FinanceLegend
    },
    computed: {
      ...mapState('Header', ['hotel']),
    },
  }
</script>