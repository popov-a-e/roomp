<template>
  <div class='card hotel-info' v-if='module_active && hotel'>
    <input v-model='hotel[language]' class='name' :disabled='!editable' @input='e => setName(e.target.value)'/>
    <label>Статус: <span @click='deactivate' class='active' v-if='!hotel.disabled'>Активен</span><span class='inactive'

                                                                                                        @click='activate'
                                                                                                       v-if='hotel.disabled'>Неактивен</span></label>
    <div class='link'>
      <a v-if='!editable' :href='`https://roomp.co/hotel/${hotel.pretty_url}`'>Ссылка на отель в roomp</a>
      <label v-if='editable'><span>Pretty URL</span><input v-model='hotel.pretty_url'
                                                           @input='e => setPrettyURL(e.target.value)'/></label>
    </div>
    <div class='buttons'>
      <button v-if='!editable' class='edit' @click='edit'>Редактировать</button>
      <button v-if='!editable' class="delete" @click="deleteHotel">Удалить</button>

      <button @click='cancel' class='cancel' v-if='editable'>Отменить</button>
      <button @click='apply' class='apply' v-if='editable'>Применить</button>
    </div>


    <div>
      <div class='lang-selector'>
        <button v-for='lang in languages' @click='selectLanguage(lang.id)' :class='{selected: lang.id === language}'>
          {{lang.name}}
        </button>
      </div>

      <div class='contacts'>
        <h4>Контактная информация</h4>
        <table class='plain'>
          <tr>
            <td>Настоящее название</td>
            <td><input v-model='hotel.regular_name' :disabled='!editable' @input='setRegularName'/></td>
          </tr>
          <tr>
            <td>Телефоны рецепции</td>
            <td><textarea v-model='hotel.reception_phone' :disabled='!editable' @input='setReceptionPhone'></textarea>
            </td>
          </tr>
          <tr>
            <td>E-mail для связи</td>
            <td><textarea v-model='hotel.reception_email' :disabled='!editable' @input='setReceptionEmail'></textarea>
            </td>
          </tr>
          <tr>
            <td>Владелец</td>
            <td v-if="hotel.hotelier">{{hotel.hotelier.organization}}</td>
          </tr>
          <tr>
            <td>Управляющий</td>
            <td v-if="hotel.hotelier">
              <label>
                <span>Имя</span>
                <input :disabled='!editable' @input="e => setHotelierName(e.target.value)" :value="hotel.hotelier.name" />
              </label>
              <label>
                <span>Телефон</span>
                <input :disabled='!editable' @input="e => setHotelierPhone(e.target.value)" :value="hotel.hotelier.phone" />
              </label>
              <label>
                <span>Email</span>
                <input disabled :value="hotel.hotelier.email" />
              </label>
            </td>
          </tr>
          <tr v-if='editable'>
            <td colspan='2'>
              <button @click='toggleHoteliers' class='hotelier-button'>Выбрать отельера</button>
              <div v-if='editable && hoteliersVisible' class='admin-hoteliers-overlay' @click='toggleHoteliers'>
                <admin-hoteliers :embedded='true' @input='setHotelier'></admin-hoteliers>
              </div>
            </td>
          </tr>
          <tr>
            <td>Контактные лица</td>
            <td>
              <table class="plain contacts" cellpadding="0" cellspacing="0">
                <tr v-for="contact in contacts">
                  <td>{{ contact.name }}</td>
                  <td>{{ contact.position }}</td>
                  <td>{{ contact.phone }}</td>
                  <td v-if="editable" class="button"><button @click="editContact(contact)"><i class="fa fa-edit"></i></button></td>
                  <td v-if="editable" class="button"><button class="delete" @click="deleteContact(contact)"><i class="fa fa-trash"></i></button></td>
                </tr>
                <tr v-if="editable">
                  <td colspan="4">
                    <button @click="createContact">+ Добавить контакт</button>
                  </td>
                </tr>
              </table>
              <div v-if="contactEdited" class="contact-popup" @click="clearContact">
                <div @click.stop="Bus.$emit('click', _uid)">
                  <form @submit.prevent="saveContact">
                    <label>
                      <span>ФИО</span>
                      <input @input="e => setContactName(e.target.value)" :value="contactEdited.name" />
                    </label>
                    <label>
                      <span>Должность</span>
                      <input @input="e => setContactPosition(e.target.value)" :value="contactEdited.position" />
                    </label>
                    <label>
                      <span>Номер телефона</span>
                      <input @input="e => setContactPhone(e.target.value)" :value="contactEdited.phone" />
                    </label>
                    <label>
                      <span>Email</span>
                      <input @input="e => setContactEmail(e.target.value)" :value="contactEdited.email" />
                    </label>
                    <label>
                      <span>Комментарий</span>
                      <textarea @input="e => setContactComment(e.target.value)" :value="contactEdited.comment"></textarea>
                    </label>

                    <div class="controls">
                      <button type="submit" class="apply">Сохранить</button>
                      <button @click="clearContact" type="button" class="cancel">Отмена</button>
                    </div>
                  </form>
                </div>
              </div>
            </td>
          </tr>
        </table>
      </div>
      <div class='map'>
        <h4>Расположение</h4>
        <table class='plain'>
          <tr>
            <td>Город</td>
            <td>
              <div class='cbutton-container'>
                <c-button :disabled='!editable' v-on:input='setCity' :name='city.name' v-for='city in cities'
                          :id='city.id'
                          :value='hotel.city_id === city.id'></c-button>
              </div>
            </td>
          </tr>
          <tr>
            <td>Адрес</td>
            <td><input v-model='hotel["address_" + language]' :disabled='!editable' @input='setAddress'/></td>
          </tr>
          <tr>
            <td colspan='2'>
              <div class='map'>

              </div>
            </td>
          </tr>
          <tr>
            <td>Координаты</td>
            <td>{{hotel.lat}}<br>{{hotel.lng}}<br><button @click="getLatLngByAddress">Получить по адресу</button></td>
          </tr>
          <tr>
            <td>Файл с маршрутом</td>
            <td>
              <div class='map-load'>
                <input type='file' v-if='editable' @change='setMapFile'/>
                <button v-if='editable' @click='applyLoad'>Загрузить</button>
              </div>
              <a :href='map_image_url(hotel.map)' target='_blank'>{{hotel.map}}</a>
            </td>
          </tr>
        </table>
      </div>
      <div class='about'>
        <h4>Об отеле</h4>
        <table class='plain'>
          <tr>
            <td>Channel Manager</td>
            <td><span v-if='hotel.channel'>{{hotel.channel.name}}</span><span v-else>Нет</span></td>
          </tr>
          <tr v-if='editable'>
            <td colspan='2'>
              <button @click='toggleChannels' class='hotelier-button'>Выбрать канал</button>
              <div v-if='editable && channelsVisible' class='admin-channels-overlay' @click='toggleChannels'>
                <admin-channels :embedded='true' @input='setChannel'></admin-channels>
              </div>
            </td>
          </tr>
          <tr>
            <td>Заезд</td>
            <td><input :disabled='!editable' v-model='hotel.checkin' @input='setCheckin'/></td>
          </tr>
          <tr>
            <td>Выезд</td>
            <td><input :disabled='!editable' v-model='hotel.checkout' @input='setCheckout'/></td>
          </tr>
          <tr>
            <td>Методы оплаты</td>
            <td>
              <div class='cbutton-container' v-if='cities.length > 0'>
                <c-button :disabled='!editable' :value='hotel.payment_online' :name='"Оплата онлайн"'
                          @input='setPaymentOnline'></c-button>
                <c-button :disabled='!editable' :value='hotel.payment_by_cash' :name='"Оплата наличными"'
                          @input='setPaymentByCash'></c-button>
                <c-button :disabled='!editable' :value='hotel.payment_by_card' :name='"Оплата картой"'
                          @input='setPaymentByCard'></c-button>
              </div>
            </td>
          </tr>
          <tr v-if='hotel_amenities.length > 0'>
            <td>Удобства в отеле</td>
            <td>
              <div class='cbutton-container'>
                <c-button :disabled='!editable' @input='setAmenity' :name='am.name' v-for='am in hotel_amenities'
                          :id='am.id'
                          :value='hotel.amenities.indexOf(am.id) >= 0'></c-button>
              </div>
            </td>
          </tr>
          <tr>
            <td>Дополнительно</td>
            <td>
              <div class='cbutton-container'>
                <c-button :disabled='!editable' @input='setBreakfast' :name='"Завтрак включен"'
                          :value='hotel.breakfast'></c-button>
              </div>
            </td>
          </tr>
        </table>
      </div>
      <div class='textual'>
        <table class='plain'>
          <tr>
            <td>Описание</td>
            <td><textarea :disabled='!editable' @input='setDescription'
                          v-model='hotel["description_"+language]'></textarea></td>
          </tr>
          <tr>
            <td>Ориентир</td>
            <td><textarea :disabled='!editable' @input='setLandmark' v-model='hotel["landmark_"+language]'></textarea>
            </td>
          </tr>
          <tr>
            <td>Дополнительно</td>
            <td><textarea :disabled='!editable' @input='setAdditional'
                          v-model='hotel["additional_"+language]'></textarea></td>
          </tr>
          <tr>
            <td>Как добраться</td>
            <td><textarea :disabled='!editable' @input='setReach' v-model='hotel["reach_"+language]'></textarea></td>
          </tr>
        </table>
      </div>
      <div class='photos'>
        <h4>Short-list фотографий</h4>
        <div class='photos-container' v-if='photos_hub' @drop.prevent.stop='photoReposition(null, true)'>
          <div
            v-if='photo'
            v-for='photo in photos_hub'
            class='room-photo'
            :class='{loaded: !!photo.src, active: editable}'
            :style='{backgroundImage: `url(${photo.content || `${image_url(photo.src, 200)}`})`}'
            :draggable='!!photo.src && editable'
            @mousedown='(editable && photo.src) && photoDrag(photo.src, true)'
            @drop.prevent.stop='(editable && photo.src) && photoReposition(photo.src, true)'
          >
            <button v-if='photo.src' @click='photoRemove(photo.src, true)'><i class='icon-close'></i></button>
          </div>
          <div v-if='photos_hub.length === 0' class='nophoto'>Нет фото</div>
        </div>
      </div>
      <div class='photos'>
        <h4>Фотографии</h4>
        <div class='photos-container' v-if='photos'>
          <div class='drag-overlay' v-if='dragover && editable' @drop.prevent.stop='photoDrop'></div>
          <div
            v-if='photo'
            v-for='photo in photos'
            class='room-photo'
            :class='{loaded: !!photo.src, active: editable}'
            :style='{backgroundImage: `url(${photo.content || image_url(photo.src, 200)})`}'
            :draggable='!!photo.src && editable'
            @dblclick="dblClick(photo.src)"
            @mousedown='(editable && photo.src) && photoDrag(photo.src)'
            @drop.prevent.stop='(editable && photo.src) && photoReposition(photo.src)'
          >
            <button v-if='photo.src' @click='photoRemove(photo.src)'><i class='icon-close'></i></button>
            <div v-if='photo.progress' class='progress-bar' :style='{width: (100 - photo.progress) + "%"}'>
            </div>
          </div>
          <div v-if='photos.length === 0' class='nophoto'>Нет фото</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import CButton from '../../components/Cbutton.vue';
  import AdminHoteliers from './Hoteliers.vue';
  import AdminChannels from './APIChannels.vue';
  import {options, appearance} from './../../components/GMap/options';
  import HotelInfo from '../store/modules/card/HotelInfo';
  import {mapState} from 'vuex';
  import Bus from './../../Bus';

  export default {
    components: {
      CButton, AdminHoteliers, AdminChannels
    },
    data () {
      return {
        marker: null,
        photos: [],
        draggingPhotoSrc: false,
        photoDragging: false,
        dragover: false,
        mapFile: false,
        photos_hub: false,
        isHub: false,
        mounted: false,
        Bus,
        dd
      }
    },
    module: {HotelInfo},
    props: ['hotel_id'],
    computed: {
      ...HotelInfo.mapProps(),
      ...mapState('Meta', ['hotel_amenities', 'cities', 'languages']),
    },
    methods: {
      ...HotelInfo.mapMethods(),
      getLatLngByAddress() {
        const city = this.cities.where('id', this.hotel.city_id).ru;
        axios.get('/geoapi/', {params: {address: city +', ' +this.hotel.address_ru}}).then(response => {
          this.setLat(response.data[0]);
          this.setLng(response.data[1]);
          this.map.setCenter(new google.maps.LatLng(this.hotel.lat, this.hotel.lng));

        });
      },
      initModule () {
        this.setID(this.hotel_id);

        if (this.hotel_amenities.length === 0) {
          this.$store.dispatch('Meta/loadAll');
        }

        if (!this.hotel_id) {
          this.createNew();

          this.$watch(function () {
            return this.hotel && this.module_active;
          }, () => {
            if (this.$el) this.loadMap();
          });
        } else {
          this.load().then(() => {
            this.loadMap();
          });
        }
      },
      setPosition(e) {
        if (!this.editable) return;

        this.setLatLng(e.latLng);
      },
      setMapFile (e) {
        this.mapFile = e.target.files[0];
      },
      applyLoad() {
        if (this.mapFile) {
          let data = new FormData();

          const index = this.photos.length - 1;

          data.append('images[]', this.mapFile, '1.jpg');

          axios.post('/upload/map', data, {
            headers: {'content-type': 'multipart/form-data'},
          }).then(response => {
            this.setMap(response.data);
          });
        }
      },
      loadMap () {
        options.center = new google.maps.LatLng(this.hotel.lat, this.hotel.lng);
        window.map = this.map = new google.maps.Map(this.$el.querySelector('.map .map'), options);

        this.map.setOptions({styles: appearance});
        this.map.setZoom(15);

        this.map.addListener('click', this.setPosition);

        this.marker = new google.maps.Marker({
          position: options.center,
          map: this.map
        });
      },
      photoRemove (src, isHub = false) {
        const photos = (isHub ? this.photos_hub : this.photos).map(p => p.src);

        const tgtPos = photos.indexOf(src);

        photos.splice(tgtPos, 1);

        isHub ? this.setPhotosHub(photos.join(',')) : this.setPhotos(photos.join(','));
      },
      dblClick(src) {
        if (!this.editable) return;

        let target = this.photos_hub.map(photo => photo.src);

        target.push(src);

        this.setPhotosHub(target.join(','));
      },
      photoReposition (src, isHub = false) {
        if (!this.draggingPhotoSrc) return;

        if (this.isHub && isHub || !this.isHub && !isHub) {
          let source = this.isHub ? this.photos_hub : this.photos;

          source = source.map(p => p.src);

          const srcPos = source.indexOf(this.draggingPhotoSrc);
          const tgtPos = source.indexOf(src);

          if (srcPos >= 0) {
            source.splice(srcPos, 1);
          }

          source.splice(tgtPos, 0, this.draggingPhotoSrc);

          if (this.isHub) this.setPhotosHub(source.join(',')); else this.setPhotos(source.join(','));
        } else if (!this.isHub && isHub) {
          let source = this.isHub ? this.photos_hub : this.photos;
          let target = isHub ? this.photos_hub : this.photos;

          source = source.map(p => p.src);
          target = target.map(p => p.src);

          const tgtPos = target.indexOf(src);
          const srcPos = source.indexOf(this.draggingPhotoSrc);

          target.splice(tgtPos, 0, this.draggingPhotoSrc);

          this.setPhotosHub(target.join(','));
        }
      },
      photoDrag (data, isHub = false) {
        this.photoDragging = true;
        this.draggingPhotoSrc = data;
        this.isHub = isHub;
      },
      photoDrop (e) {
        this.dragover = false;

        const files = e.dataTransfer.files;

        [...files].forEach(file => {
          let data = new FormData();

          this.photos.push({
            progress: 0
          });
          const index = this.photos.length - 1;

          data.append('images[]', file, '1.jpg');

          const Reader = new FileReader();

          Reader.readAsDataURL(file);
          Reader.onload = () => {
            Vue.set(this.photos[index], 'content', Reader.result);
          };

          axios.post('/upload/photo', data, {
            headers: {'content-type': 'multipart/form-data'},
            onUploadProgress: progressEvent => {
              this.photos[index].progress = Math.round(100 * progressEvent.loaded / progressEvent.total)
            }
          }).then(response => {
            Vue.set(this.photos[index], 'src', response.data);
            this.setPhotos(this.photos.map(p => p.src).join(','));
          });
        });
        return false;
      },
    },
    created () {
      Bus.$on('keydown', e => {
        if (e.code === 'Escape') {
          this.dragover = false;
        }
      });

      Bus.$on('mousemove', (x, y, e) => {
        if (!e.which) this.photoDragging = false;
      });

      this.$once('module_activated', () => {
        this.$watch('photosOriginal', value => this.photos = value);
        this.$watch('photoHub', value => this.photos_hub = value);
        this.$watch('hotel', value => {
          if (this.marker) this.marker.setPosition(new google.maps.LatLng(value.lat, value.lng));
        }, {deep: true});
      });
    },
    mounted () {
      Bus.$on('dragover', e => {
        const photos = this.$el.querySelector('.photos-container');

        this.dragover = this.editable && !this.photoDragging && this.$el.contains(e.target);

        this.photosDragover = this.editable && this.photoDragging && photos.contains(e.target);
      });
    }
  }
</script>