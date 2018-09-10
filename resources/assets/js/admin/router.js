import Reservations from './reservations/Reservations.vue';
import Reservation from './reservations/Reservation.vue';
import Index from './index/AdminIndex.vue';
import Hotels from './hotels/Hotels.vue';
import Integrations from './hotels/Channels.vue';
import Hotel from './hotels/Hotel.vue';
import HotelInfo from './hotels/HotelInfo.vue';
import Policies from './hotels/Policies.vue';
import Room from './hotels/Room.vue';
import Reconcilliation from './hotels/Reconcilliation.vue';
import Rates from './rates/AdminRates.vue';
import Stub from './router/Stub.vue';
import Availability from './hotels/Availability.vue';
import Organization from './hotels/Organization.vue';
import Prices from './hotels/Prices.vue';
import PromoCodes from './promo/PromoCodes.vue';
import PromoCode from './promo/PromoCode.vue';
import BusinessDevelopers from './bd/BusinessDevelopers.vue'
import BusinessDeveloper from './bd/BusinessDeveloper.vue';
import Offer from './hotels/Offer.vue';
import Logs from './hotels/Logs.vue';
//import Countries from './countries/Countries.vue';

import MonthlyFinance from './reservations/MonthlyFinance.vue';

import ReservationCreator from './reservations/ReservationCreator.vue';

import VueRouter from 'vue-router';
Vue.use(VueRouter);


const router = new VueRouter({
  routes: [
    {
      path: '/',
      meta: {name: 'Главная'},
      component: Index,
      children: [
        {
          path: '',
          redirect: '/hotels'
        },
        {
          path: 'hotels',
          meta: {name: 'Отели'},
          component: Stub,
          children: [
            {
              path: '',
              component: Hotels
            },
            {
              path: 'new',
              meta: {
                name: 'Новый отель',
              },
              component: HotelInfo,
              props: true
            },
            {
              path: ':hotel_id',
              meta: {
                name: '$hotel_id',
                id_key: 'hotel_id',
                state: 'Hotels#/hotels.hotels'
              },
              component: Stub,
              props: true,
              children: [
                {path: '', meta: {name: '$hotel_id', id_key: 'hotel_id', state: 'Hotels#/hotels.hotels', resource: 'hotels'}, props: true, component: Hotel},
                {path: 'info', meta: {name: 'Инфо',}, props: true, component: HotelInfo},
                {path: 'new', meta: {name: 'Новая комната',}, props: true, component: Room},
                {path: 'rates', meta: {name: 'Roomp Rates',}, props: true, component: Rates},
                {path: 'availability', meta: {name: 'Доступность',}, props: true, component: Availability},
                {
                  path: 'prices',
                  meta: {
                    name: 'Цены',
                  },
                  props: true,
                  component: Prices
                },
                {path: 'integrations', meta: {name: 'Интеграции',}, props: true, component: Integrations},
                {path: 'reconcilliation', meta: {name: 'Сверка',}, props: true, component: Reconcilliation},
                {path: 'policies', meta: {name: 'Policies',}, props: true, component: Policies},
                {path: 'organization', meta: {name: 'Организация',}, props: true, component: Organization},
                {path: 'offer', meta: {name: 'Оферта'}, props: true, component: Offer},
                {path: 'logs', meta: {name: 'История'}, props: true, component: Logs},
                {path: ':room_id', meta: {name: 'Комната $room_id', id_key: 'room_id', resource: 'rooms', state: 'Room#/hotels/$hotel_id/$room_id.room'}, props: true, component: Room},
              ]
            },
          ]
        },
        {
          path: 'reservations',
          meta: {name: 'Бронирования'},
          component: Stub,
          children: [
            {
              path: '',
              component: Reservations
            },
            {
              path: 'create',
              meta: {name: 'Новое бронирование'},
              component: ReservationCreator
            },
            {
              path: ':reservation_id',
              meta: {
                name: 'Бронирование $reservation_id',
                id_key: 'reservation_id'
              },
              component: Reservation,
              props: true
            },
          ]
        },
        {
          path: 'promo',
          meta: {name: 'Промо-коды'},
          component: Stub,
          children: [
            {path: '', component: PromoCodes},
            {path: 'create', meta: {name: 'Новый промо-код'}, component: PromoCode, props: true},
            {path: ':promo_code_id', meta: {name: 'Промо-код $promo_code_id', id_key: 'promo_code_id', state: 'PromoCodes#/promo.promo', resource: 'promo_codes'}, component: PromoCode, props: true},
          ]
        },
        {path: 'bd', meta: {name: 'Бизнес-девелоперы'}, component: Stub,
          children: [
            {path: '', component: BusinessDevelopers},
            {path: 'new', meta: {name: 'Новый BD'}, component: BusinessDeveloper, props: true},
            {path: ':business_developer_id', meta: {name: 'BD $business_developer_id', id_key: 'business_developer_id', state: 'BusinessDevelopers#/business_developers.name', resource: 'business_developers'}, component: BusinessDeveloper, props: true},
          ]
        },
        {"path": "finance", meta: {name: 'Финансы'},component: MonthlyFinance},
       // {path: 'countries', meta: {name: 'Страны'}, component: Countries}
      ]
    },
    { path: '*', redirect: '/' }
  ]
});


export default router;