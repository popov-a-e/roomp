<template>
  <div
    class='card room'
    v-if='module_active && room'
  >
    <div class='drag-overlay' v-if='dragover && editable' @drop.prevent.stop='photoDrop'></div>
    <h2>{{ room_id ? `Комната ${room_id}` : 'Новая комната' }}</h2>
    
    <div class='buttons'>
      <button v-if='!editable && !deleteConfirm' class='edit' @click='edit'>Редактировать</button>
      <button v-if='!editable && !deleteConfirm' class='delete-check' @click='deleteCheck'>Удалить</button>
  
      <button v-if='deleteConfirm' class='delete' @click='confirmDelete'>Удалить</button>
      <button v-if='deleteConfirm' class='delete-reset' @click='deleteReset'>Отмена</button>
      
      
      <button @click='cancel' class='cancel' v-if='editable'>Отменить</button>
      <button @click='apply' class='apply' v-if='editable'>Применить</button>
    </div>

    <div class='info'>
      <label>
        <span>Название</span>
        <input :disabled='!editable' v-model='room.name' @input='setName'/>
      </label>
      <label>
        <span>Количество</span>
        <input :disabled='!editable' v-model='room.number' @input='setNumber'/>
      </label>
      <label>
        <span>Вместимость</span>
        <input :disabled='!editable' v-model='room.max_guest_number' @input='setGuestNumber'/>
      </label>
      <label>
        <span>Размер (м2)</span>
        <input :disabled='!editable' v-model='room.size' @input='setSize'/>
      </label>
    </div>
    
    <div>
      <h3>Класс номера</h3>
      <div class='btn-container'>
        <c-button :disabled='!editable' v-on:input='setClass' :name='cl.name' v-for='cl in room_classes' :id='cl.id'
                  :value='room.room_class_id === cl.id'></c-button>
      </div>
    </div>
    
    <div v-if='room.allotments'>
      <h3>Размещение</h3>
      <div class='btn-container'>
        <c-button :disabled='!editable'
                  v-on:input='setAllotment'
                  :name='al.name'
                  v-for='al in allotments'
                  :id='al.id'
                :value='room.allotments.indexOf(al.id) >= 0'></c-button>
      </div>
    </div>
    
    <div v-if='room.amenities'>
      <h3>Удобства</h3>
      <div class='btn-container'>
        <c-button :disabled='!editable' v-on:input='setAmenity' :name='am.name' v-for='am in room_amenities' :id='am.id'
                :value='room.amenities.indexOf(am.id) >= 0'></c-button>
      </div>
    </div>
    <div class='photos'
         @drop.prevent.stop='e => editable ? hotelPhotoDrop(e) : false'
         :class='{dragover: photosDragover}'
    >
      <div
        v-if='photo'
        v-for='photo in photos'
        class='room-photo'
        :class='{loaded: !!photo.src, active: editable}'
        :style='{backgroundImage: `url(${photo.content || image_url(photo.src, 200)})`}'
        :draggable='!!photo.src && editable'
        @mousedown='(editable && photo.src) && photoDrag(photo.src)'
        @drop.prevent.stop='(editable && photo.src) && photoReposition(photo.src)'
      >
        <button v-if='photo.src' @click='photoRemove(photo.src)'><i class='icon-close'></i></button>
        <div v-if='photo.progress' class='progress-bar' :style='{width: (100 - photo.progress) + "%"}'>
        </div>
      </div>
      <div v-if='photos.length === 0' class='nophoto'>Нет фото</div>
    </div>
    
    <div class='hotel-photos'>
      <div
        v-if='photo'
        v-for='photo in hotelPhotos'
        class='photo'
        :class='{active: editable}'
        :style='{backgroundImage: `url(${image_url(photo, 200)}`}'
        :draggable='editable'
        @mousedown='editable && photoDrag(photo)'
      >
      </div>
    </div>
  </div>
</template>

<script>
  import CButton from '../../components/Cbutton.vue';
  import Room from '../store/modules/card/Room';
  import {mapState, mapActions, mapMutations, mapGetters} from 'vuex';
  import Bus from './../../Bus';

  export default {
    components: {
      CButton
    },
    
    data () {
      return {
        dragover: false,
        photos: [],
        photoDragging: false,
        draggingPhotoSrc: '',
        photosDragover: false
      }
    },
    module: {Room},
    props: ['room_id', 'hotel_id'],
    computed: {
      id () {
        return this.room_id || 'new';
      },
      ...Room.mapProps(),
      ...mapState('Meta', ['room_classes', 'allotments', 'room_amenities'])
    },
    methods: {
      ...Room.mapMethods(),
      initModule () {
        this.setID(this.room_id || null);
        this.setHotelID(this.hotel_id);

        const load = () => {
          if (this.room_id) {
            this.load();
          } else {
            this.createNew();
          }
        };
        
        if (this.room_classes.length === 0) {
          this.$store.dispatch('Meta/loadAll').then(load);
        } else {
          load();
        }
      },
      hotelPhotoDrop (e) {
        if (!this.draggingPhotoSrc || this.photos.map(p => p.src).indexOf(this.draggingPhotoSrc) >= 0) return;
        this.photos.push({src: this.draggingPhotoSrc});

        this.hotelPhotosUpdate();
      },
      photoRemove (src) {
        const photos = this.photos.map(p => p.src);
        const hotelPhotos = this.hotelPhotos;

        const tgtPos = photos.indexOf(src);

        photos.splice(tgtPos, 1);
        hotelPhotos.push(src);

        this.setPhotos(photos.join(','));
        this.setHotelPhotos(hotelPhotos);
      },
      photoReposition (src) {
        if (!this.draggingPhotoSrc) return;

        const photos = this.photos.map(p => p.src);

        const tgtPos = photos.indexOf(src);
        const srcPos = photos.indexOf(this.draggingPhotoSrc);

        if (srcPos >= 0) {
          photos.splice(srcPos, 1);
        }

        photos.splice(tgtPos, 0, this.draggingPhotoSrc);

        this.photos = photos.map(p => {
          return {
            src: p
          }
        });

        this.hotelPhotosUpdate();
      },
      hotelPhotosUpdate() {
        const pos = this.hotelPhotos.indexOf(this.draggingPhotoSrc);
        this.photosDragover = false;

        if (pos >= 0) {
          let photos = this.hotelPhotos;
          //photos.splice(pos, 1);

          //this.setHotelPhotos(photos);
        }

        this.draggingPhotoSrc = null;
        this.setPhotos(this.photos.map(p => p.src).join(','));
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
      photoDrag (data) {
        this.photoDragging = true;
        this.draggingPhotoSrc = data;
      }
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

      this.$once('module_activated', () => this.$watch('photosOriginal', val => this.photos = val));
    },
    mounted () {
      Bus.$on('dragover', e => {
        const photos = this.$el.querySelector('.photos');

        this.dragover = this.editable && !this.photoDragging && this.$el.contains(e.target);

        this.photosDragover = this.editable && this.photoDragging && photos.contains(e.target);
      });
    }
  }
</script>