@component('email.reservation.layout')
  <div class='reminder'>
    <div class='greeting'>
      <p>Уважаемый партнер,</p>
      <p>Мы рады, что Вы решили расширить партнерство с нами!</p>
      <p>Для подключения отеля <b>{{ $hotel->regular_name }}</b> Вам необходимо выполнить 2 шага:</p>
      <ol>
        <li>Зайти в экстранет по <a href='{{ "https://$subdomain.roomp.co/hotel/$id?token=$token"}}'>cсылкe</a>  и принять электронную оферту
        </li>
        <li>Дождаться наших коллег, которые завезут в отель брендированную продукцию и сделают красивые фотографии
          номеров
        </li>
      </ol>
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
