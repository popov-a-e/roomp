<main>
  <div class="header-container">
    <header>
      <a href='/' class='logo'></a>

      <hotel-selector :hotels="{{ $hotels }}" :id="{{ $currentID }}"></hotel-selector>
    </header>
  </div>
  <div class="content">
    <new-hotel-form :hotel="{{ $hotel }}" />
  </div>
</main>