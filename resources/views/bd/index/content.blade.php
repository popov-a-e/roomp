<main>
  <b-d-header :user='{{ $user }}'></b-d-header>

  <keep-alive>
    <router-view></router-view>
  </keep-alive>

  <div class="modal" v-if="addingHotel" v-on:click="hideHotel">
    <new-hotel></new-hotel>
  </div>

  <div class="modal" v-if="selectedRoom" v-on:click.stop="hideRoom">
    <room></room>
  </div>

  <div class="modal" v-if="organizationSelectorVisible" v-on:click="hideOrganizationSelector">
    <organization-selector></organization-selector>
  </div>

  <div class="modal" v-if="hotelierSelectorVisible" v-on:click="hideHotelierSelector">
    <hotelier-selector></hotelier-selector>
  </div>
</main>