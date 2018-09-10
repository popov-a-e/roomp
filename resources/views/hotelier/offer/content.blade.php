<main>
  <div class="header-container">
    <header>
      <a href='/' class='logo'></a>

      <a class="logout" href="/logout">Выйти</a>


      <hotel-selector :hotels="{{ $hotels }}" :id="{{ $currentID }}"></hotel-selector>
    </header>
  </div>
  <div class="content">
    <div>
      <iframe src="/docs/offer"></iframe>
      <form action="/accept_offer" @if(!$old) v-on:submit.prevent.stop="accept" @endif>
        <label>
          <input type="checkbox" name="acceptance" v-model="acceptance"/>
          <span>Я принимаю условия договора-оферты</span>
        </label>

        <button :disabled="!acceptance" type="submit">
          <span v-if="!loading">Акцепт</span>
          <i v-if="loading" class="fa fa-spinner fa-spin"></i>
        </button>
      </form>
    </div>

    @if (!$old)
      <div class="modal" v-if="accepted">
        <div>
          <p>На вашу почту была отправлена инструкция по подключению к Roomp с памяткой по обслуживанию клиентов</p>
          <span>Пожалуйста, ознакомьтесь с информацией, чтобы продолжить работу</span>

          <a href="/">OK</a>
        </div>
      </div>
    @endif
  </div>
</main>