"use strict";

import '../bootstrap.js';

import Bus from '../Bus';

import store from './store/store.js';

import router from './router';

import ExtranetHeader from './components/ExtranetHeader/ExtranetHeader.vue';
import ExtranetFooter from './components/ExtranetFooter/ExtranetFooter.vue';
import ExtranetIndex from './index/ExtranetIndex.vue';
import DashboardEditor from './dashboard/DashboardEditor.vue';
import Editor from './organization/popup/Editor.vue'

Vue.use(Vuex);
import {mapState, mapMutations} from 'vuex';

document.addEventListener('click', e => Bus.$emit('click'));
document.addEventListener('mouseup', e => Bus.$emit('mouseup'));
document.addEventListener('mousedown', e => Bus.$emit('mousedown', e));
document.addEventListener('mousemove', e => Bus.$emit('mousemove', e.pageX, e.pageY, e));
document.addEventListener('keydown', e => Bus.$emit('keydown', e));

window.addEventListener('dragenter', e => {Bus.$emit('dragenter',e);});
window.addEventListener('dragleave', e => {Bus.$emit('dragleave', e);});
window.addEventListener('resize', e => Bus.$emit('resize', e));

const App = new Vue({
  data: {
    Bus: Bus
  },
  el: 'main',
  store,
  router,
  components: {
    ExtranetHeader, ExtranetIndex, DashboardEditor, ExtranetFooter, Editor
  },
  computed: {
    ...mapState('DashboardEditor', {dashboard_editor_visible: 'is_visible'}),
    ...mapState('Organization/Editor', {organization_editor_visible: 'map'}),
  },
  methods: {
    ...mapMutations('DashboardEditor', {hideDashboardEditor: 'hide'}),
    ...mapMutations('Organization', {hideOrganizationEditor: 'cancel'}),
  },
});