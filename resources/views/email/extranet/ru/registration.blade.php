@component('email.reservation.layout', ['locale' => $locale])
  <div class='reminder'>
    <div class='greeting'>
      <p>Уважаемый партнер,</p>
      @if ($isNewAccount)
        <p>Спасибо за интерес к сотрудничеству с компанией <b>Roomp!</b></p>
        <p>Чтобы начать работать с сетью отелей Roomp,
          пройдите быструю регистрацию по
          <a href='{{ "https://$subdomain.roomp.co/register/{$id}?token=$token"}}'>ссылкe</a></p>
        <p>При регистрации нужно придумать логин и пароль.
          Будьте внимательны: этот логин и пароль будут использоваться для входа в личный кабинет Roomp (Экстранет) и при подключении к Channel Manager. </p>
      @else
        <p>Мы рады, что Вы решили расширить партнерство с нами!</p>
        <p>Для подключения отеля <b>{{ $hotel->regular_name }}</b> Вам необходимо пройти по <a href='{{ "https://$subdomain.roomp.co/new_hotel/{$hotel->id}?token=$token"}}'>ссылкe</a> и подтвердить добавление нового объекта к вашему аккаунту.</p>
        <p>Обращаем внимание, что доступ в личный кабинет отеля будет доступен по логину и паролю, который вы использовали при регистрации.</p>
      @endif
      <style>
        p, li {
          font-size: 14px !important;
        }

        div.footer p {
          margin: 0;
          padding: 0;
          text-align: left;
        }
      </style>
      <div class="footer">
        <p>Остались вопросы?</p>
        <p>Мы всегда готовы помочь!</p>
        <p><b>8 (800) 777 17 56</b></p>
        <p><b>partners@roomp.co</b></p>

        <p style="float: right;">Команда <b>Roomp</b></p>
      </div>
    </div>
  </div>
@endcomponent
