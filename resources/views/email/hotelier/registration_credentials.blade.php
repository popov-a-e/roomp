@component('email.reservation.layout')
  <div class='reminder'>
    <div class='greeting'>
      <p>Здравствуйте, партнер! </p>

      <p>Спасибо за регистрацию в экстранете Roomp.
        <br>
        Ваши данные для входа в экстранет: <br>
        Ссылка для входа в экстранет: <a href="https://extranet.roomp.co">extranet.roomp.co</a> </p>
      <p style="font-weight: bolder;">Логин: {{ $email }}<br>
        Пароль: {{ $password }} </p>

      <p>Пожалуйста, сохраните это письмо, чтобы логин и пароль всегда были под рукой.</p>
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
        <p>Если вы не можете зайти в экстранет, свяжитесь с нами по почте или телефону, указанным ниже:</p>
        <p><b>8 (800) 777 17 56</b></p>
        <p><b>help@roomp.co</b></p>
        <p style="float: right; clear: both;">С уважением,</p>
        <p style="float: right; clear: both;">Служба поддержки <b>Roomp</b></p>

      </div>
    </div>
  </div>
@endcomponent
