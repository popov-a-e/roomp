@component('email.reservation.layout', ['locale' => $locale])
  <div class='reminder'>
    <div class='greeting'>
      <p>Уважаемый партнер,</p>
      @if ($isNewAccount)
      <p>Поздравляем вас с успешной регистрацией в <b>Roomp</b>!</p>
      <p>Вы стали партнером сети отелей Roomp и получили доступ в личный кабинет (Экстранет) Roomp. Мы рекомендуем
        сохранить это письмо, чтобы данные для входа всегда были под рукой.</p>
      <p><b>Ваши данные для входа в Roomp:</b></p>
      <p><b>Ссылка на <a href='{{ "https://$subdomain.roomp.co/"}}'>экстранет</a><br></b>
        <b>Ваш логин: {{ $email }}</b><br>
        <b>Пароль: {{ $password }}</b><br>
        <b>Ваш объект: {{ $hotel->regular_name }} (ID {{ $hotel->id }})</b></p>
      @else
        <p>Выш объект "{{ $hotel->regular_name }}" был успешно добавлен в <b>Roomp</b>!</p>
        <p><b>ID объекта: {{ $hotel->id }}</b></p>
      @endif
      <p>При регистрации вы приняли условия <a href='{{ "https://$subdomain.roomp.co/docs/offer"}}'>электронной оферты</a>. Ознакомиться с электронной
        офертой вы также можете в личном кабинете Roomp, раздел “Оферта”. </p>
      <p><b>С чего начать работу с нами?</b></p>
      <ol>
        <li>
          <b>Распечатать памятку по обслуживанию гостей Roomp</b>
          <p>Для администратора вашего отеля мы подготовили <a href='{{ "https://$subdomain.roomp.co/docs/reminder"}}'>памятку по обслуживанию гостей Roomp</a> - в ней есть правила
            обслуживания гостей Roomp и контакты для связи с нашей командой поддержки. Рекомендуем распечатать памятку и
            разместить ее в удобном для администратора месте. </p>
        </li>
        <li>
          <b>Подключиться к каналу продаж Roomp в вашем Channel Manager</b>
          @if (!$channel_manager)
            <p>Если вы подключены к какому-либо Channel Manager, свяжитесь с нами по телефону <a href="tel:+78122428458">+7 (812) 242-84-58</a>. </p>
          @else

            <p>Чтобы подключиться к каналу продаж Roomp в вашем Channel Manager (WuBook / OtelMS / Travelline),
              воспользуйтесь инструкцией по <a href='{{ "https://$subdomain.roomp.co/docs/instruction"}}'>ссылке</a>. Обращаем ваше внимание, что при настройке соединения вам понадобятся
              логин и пароль, указанные при регистрации в экстранете Roomp. </p>
          @endif
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

        <p>Ждем вашего подключения и добро пожаловать в сеть отелей Roomp!</p>
        <p>Остались вопросы?</p>
        <p>Мы всегда готовы помочь!</p>
        <p><b>8 (800) 777 17 56</b></p>
        <p><b>partners@roomp.co</b></p>
        <p style="float: right; clear: both;">Команда <b>Roomp</b></p>

      </div>
    </div>
  </div>
@endcomponent
