"use strict";

import Editor from './modules/Editor'

export default {
  namespaced: true,
  state() {
    return {
      fields: [],
      contacts: [],

      companyFormHeight: 0,
      bankFormHeight: 0,
    }
  },
  mutations: {
    deleteContact: (state, id) => {
      const index = state.contacts.indexOf(state.contacts.find(el => id === el.id));
      if (index >= 0) state.contacts.splice(index, 1)
    },
    setCompanyFormHeight: (state, value) => {
      state.companyFormHeight = value;
    },
    setBankFormHeight: (state, value) => {
      state.bankFormHeight = value;
    },

    setContacts: (state, param) => {
      state.contacts = param;
    },
    setFields: (state, param) => {
      state.fields = param;
    },

    setBankData: (state) => {
      let arr = [];
      if (state.fields.length > 0) {
        arr = state.fields.filter(item => {
          return item.type.is_bank_field
        })
      }
      let map = {};
      arr.forEach(field => {
        map[field.type.name] = field.field_value;
      });

      state.Editor.currentMap = Object.assign({}, map);
      state.Editor.map = map;
      state.Editor.url = '/organization/';
      state.Editor.translationsPath = 'extranet.organization.requisites';
      state.Editor.callback = (updatedBankData) => {
        state.fields = updatedBankData;
        state.Editor.map = null;
      }
    },

    setContactData: (state, id) => {
      let arr = ['name', 'position', 'email', 'phone'];
      let map = {};
      let person = state.contacts.where('id', id);

      if (id) {
        state.Editor.id = id;
      }

      arr.forEach(key => {
        map[key] = person ? person[key] : null
      });

      state.Editor.currentMap = Object.assign({}, map);
      state.Editor.map = map;
      state.Editor.url = '/updateContactFaces/';
      state.Editor.translationsPath = 'extranet.organization.contacts';
      state.Editor.callback = (contact) => {
        const index = state.contacts.indexOf(person);
        if (index === -1) state.contacts.push(contact); else state.contacts.splice(index, 1, contact);
        state.Editor.map = null;
      }
    },

    cancel: (state) => {
      state.Editor.map = null;
      state.Editor.url = null;
      state.Editor.translationsPath = '';
      state.Editor.id = null;
    }
  },

  actions: {
    deleteContact({state, commit}, id) {
      axios.delete('/deleteContact/' + id).then(response => {
        commit('deleteContact', id)
      })
    },
    load({state, rootState, commit}) {
      axios.get('/organization/' + rootState.Header.hotel.id,).then(response => {
        commit('setFields', response.data.fields);
        commit('setContacts', response.data.contacts)
      });
    },
  },

  getters: {
    companyName: state => {
      const field = state.fields.find(field => field.type.name === 'name');

      return field ? field.field_value : __('extranet.organization.company_requisites');
    },
    companyRequisities: state => state.fields.filter(field => !field.type.is_bank_field),
    bankRequisities: state => state.fields.filter(field => field.type.is_bank_field),
    contactsFormHeight: state => state.companyFormHeight + state.bankFormHeight,
  },
  modules: {
    Editor
  }
};
