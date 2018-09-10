import RoompAboutRu from './components/ru/RoompAbout.vue';
import RoompContactsRu from './components/ru/RoompContacts.vue';
import RoompAboutEn from './components/en/RoompAbout.vue';
import RoompContactsEn from './components/en/RoompContacts.vue';
import RoompAboutCh from './components/ch/RoompAbout.vue';
import RoompContactsCh from './components/ch/RoompContacts.vue';


const about = {
  ru: RoompAboutRu,
  en: RoompAboutEn,
  ch: RoompAboutCh
};

const contacts = {
  ru: RoompContactsRu,
  en: RoompContactsEn,
  ch: RoompContactsCh
};

import VueRouter from 'vue-router';
Vue.use(VueRouter);

export default new VueRouter({
  routes: [
    { path: '/', component: about[window.locale] },
    { path: '/contacts', component: contacts[window.locale] }
  ]
});