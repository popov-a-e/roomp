<main>

  <extranet-header :user='{{ $user }}'
                   :hotels='{{ $hotels }}'
                   :hotel='{{ $hotel }}'
                   :locale="'{{ $locale }}'"
                   :reminder="{{ json_encode($reminder) }}"
                   :instruction="{{ json_encode($instruction) }}"
                     ></extranet-header>

  <keep-alive>
    <router-view></router-view>
  </keep-alive>

  <!--
  <extranet-footer></extranet-footer>
  <selection-window></selection-window>
-->
  <div class="modal" v-if="dashboard_editor_visible" v-on:click="hideDashboardEditor">
    <dashboard-editor></dashboard-editor>
  </div>

  <div class="modal" v-if="organization_editor_visible" v-on:click="hideOrganizationEditor">
    <editor></editor>
  </div>

</main>