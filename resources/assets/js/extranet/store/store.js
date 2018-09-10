"use strict";
import Header from '../components/ExtranetHeader/store/Header.js';
import Footer from '../components/ExtranetFooter/store/Footer.js'
import Reservations from '../reservations/store/Reservations';
import DashboardTable from '../dashboard/store/DashboardTable'
import DashboardEditor from '../dashboard/store/DashboardEditor';
import Finance from '../finance/store/Finance';
import Reconciliation from '../reconciliation/store/Reconciliation';
import ReservationConfirmation from '../reservations/confirmation/store/ReservationConfirmation';
import Organization from '../organization/store/Organization'

import SelectionWindow from '../dashboard/store/SelectionWindow';

export default new Vuex.Store({
  state: {},

  mutations: {
  },

  modules: {
    Header,
    Footer,
    Reservations,
    DashboardTable,
    DashboardEditor,
    SelectionWindow,
    Finance,
    Reconciliation,
    ReservationConfirmation,
    Organization,
  },
});