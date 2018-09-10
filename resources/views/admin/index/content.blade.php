<main>

  <admin-header :user='{{ auth('admins')->user()  }}'></admin-header>

  <admin-sidebar></admin-sidebar>

  <keep-alive>
    <router-view></router-view>
  </keep-alive>
</main>