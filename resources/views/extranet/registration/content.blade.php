<main>
  <div class="header-container">
    <header>
      <img src="{{ cdn('/img/logo.png') }}" class="logo"/>
    </header>
  </div>
  <div class="content">
    <extranet-registration
      :token="'{{ $token }}'" :reception-email="'{{ $reception_email }}'" :offer_accepted="{{ $offer_accepted ? 'true' : 'false' }}" :hotel_id="{{ $id }}"
    ></extranet-registration>
  </div>
</main>