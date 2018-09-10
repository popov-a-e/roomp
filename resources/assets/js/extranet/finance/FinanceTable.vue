<template>
  <div class="finance">
    <table>
      <tr class="grey">
        <td>{{__('extranet.finance.period')}}</td>
        <td>{{__('extranet.finance.sum')}}</td>
        <td>{{__('extranet.finance.agent_payment')}}</td>
        <td>{{__('extranet.finance.net_revenue')}}</td>
        <td>{{__('extranet.finance.agent_report')}}</td>
        <td>{{__('extranet.finance.act_services')}}</td>
        <td>{{__('extranet.finance.act_mutual_settlement')}}</td>
        <td>{{__('extranet.finance.invoice')}}</td>
        <!--
        <td>{{__('extranet.finance.status')}}</td>
        -->
      </tr>

      <tr v-for="document in documents" class="small">
        <td>{{ __('dates.full')[document.month - 1] }} {{document.year}}</td>
        <td v-html="toCurrency(document.total)"></td>
        <td v-html="toCurrency(document.agent_total)"></td>
        <td v-html="toCurrency(document.hotel_total)"></td>
        <td>
          <a v-if="document.generated" target="_blank" :href="`/finance/doc/${document.year}/${document.month}/${document.hotel_id}/report`"><i class="far fa-file-excel"></i> {{`O${document.hotel_id}-${document.year}/${document.month}`}}</a>
          <span v-else>{{__('extranet.finance.no_document')}}</span>
        </td>
        <td>
          <a v-if="document.generated" target="_blank" :href="`/finance/doc/${document.year}/${document.month}/${document.hotel_id}/act`"><i class="far fa-file-pdf"></i> {{`A${document.hotel_id}-${document.year}/${document.month}`}}</a>
          <span v-else>{{__('extranet.finance.no_document')}}</span>
        </td>
        <td>
          <a v-if="document.generated" target="_blank" :href="`/finance/doc/${document.year}/${document.month}/${document.hotel_id}/reconciliation_act`"><i class="far fa-file-pdf"></i> {{`R${document.hotel_id}-${document.year}/${document.month}`}}</a>
          <span v-else>{{__('extranet.finance.no_document')}}</span>
        </td>
        <td>
          <template v-if="document.agent_total < document.credit">
          <a v-if="document.generated" target="_blank" :href="`/finance/doc/${document.year}/${document.month}/${document.hotel_id}/invoice`"><i class="far fa-file-pdf"></i> {{`B${document.hotel_id}-${document.year}/${document.month}`}}</a>
          <span v-else>{{__('extranet.finance.no_document')}}</span>
          </template>
          <span v-else>{{__('extranet.finance.no_bill')}}</span>
        </td>
        <!--
        <td><payment-status-icon :type="document.credit < document.agent_total ? document.paid ? 'paid' : 'unpaid' : null"></payment-status-icon></td>
        -->
      </tr>

      <tr v-if="documents.length === 0" class="empty">
        <td colspan="8">{{__('extranet.finance.no_finance_records')}}</td>
      </tr>
    </table>
  </div>
</template>

<script>
  import {mapState, mapActions} from 'vuex';
  import PaymentStatusIcon from './PaymentStatusIcon.vue';

  export default {
    components: {
      PaymentStatusIcon
    },
    computed: {
      ...mapState('Finance', ['documents']),
    },
    methods: {
      ...mapActions('Finance', ['getDocuments', 'getDiscount'])
    },
    created () {
      this.getDiscount();
      this.getDocuments();
    }
  }
</script>