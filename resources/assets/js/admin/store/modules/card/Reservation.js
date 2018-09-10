"use strict";

import mapper from '../../../helpers/mapper';
import router from '../../../router';

export default mapper({
  code: null,
  reservation: {default: null},
  admin_comment: null,
  ostrovok_phone: null,
  edited: {
    default: false,
    function: state => state.edited = !state.edited,
    mutation: 'toggleEdit'
  }
}, {
  namespaced: true,
  state: {},
  mutations: {},
  actions: {
    load ({commit, state}) {
      axios.get('/reservation/' + state.code).then(response => {
        commit('setReservation', response.data);
        commit('setAdminComment', response.data.admin_comment);
      });
    },
    setAdminComment({commit, state}) {
      axios.post(`/reservation/${state.code}/add_comment`, {admin_comment: state.admin_comment})
        .then(response => {
          if (state.edited) commit('toggleEdit');
          alert('Ok')
        });
    },
    miss({dispatch, state}) {
      if (!confirm('Вы уверены?')) return;
      axios.get(`/reservation/${state.code}/miss`)
        .then(response => dispatch('load'))
        .catch(err => {
          alert(err.response.data)
        });
    },
    del({dispatch, state}) {
      if (!confirm('Вы уверены? Это действие невозможно отменить')) return;
      axios.delete(`/reservation/${state.code}`)
        .then(response => {
          dispatch('Router/routeClose', '/reservations/' + state.code, {root: true});
          router.push('/reservations');
        })
        .catch(err => {
          alert(err.response.data)
        });
    },
    cancel({dispatch, state}) {
      if (!confirm('Вы уверены?')) return;
      axios.get(`/reservation/${state.code}/cancel`)
        .then(response => dispatch('load'))
        .catch(err => {
          alert(err.response.data)
        });
    },
    overbooking({dispatch, state}) {
      if (!confirm('Вы уверены?')) return;
      axios.get(`/reservation/${state.code}/overbooking`)
        .then(response => dispatch('load'))
        .catch(err => {
          alert(err.response.data)
        });
    },
    resendEmail({state}) {
      if (!confirm('Вы уверены?')) return;
      axios.get(`/reservation/${state.code}/resend_email`)
        .then(response => alert('Ok'))
        .catch(err => {
          alert(err.response.data)
        });
    },
    resendEmailToHotelier({state}) {
      if (!confirm('Вы уверены?')) return;
      axios.get(`/reservation/${state.code}/resend_email_to_hotelier`)
        .then(response => alert('Ok'))
        .catch(err => {
          alert(err.response.data)
        });
    },
    getOstrovokPhone({state, commit}) {
      axios.get(`/reservation/${state.code}/get_ostrovok_phone`)
        .then(response => commit('setOstrovokPhone', response.data))
        .catch(err => {
          alert(err.response.data)
        });
    },
    setActive({commit, state}, online) {
      if (!confirm('Вы уверены?')) return;
      axios.get(`/reservation/${state.code}/push_status`, {params: {online}})
        .then(response => window.location.reload())
        .catch(err => {
          alert(err.response.data)
        });
    },
    channelPush({state}) {
      if (!confirm('Вы уверены?')) return;
      axios.get(`/reservation/${state.code}/channel_push`)
        .then(response => alert('OK'))
        .catch(err => {
          alert(err.response.data)
        });
    }
  }
});