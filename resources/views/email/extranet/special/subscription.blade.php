@component('email.reservation.layout', ['locale' => 'ru'])
  <style>
    .sub {
      width: 570px;
      float: left;
    }

    .sub > img {
      max-width: 570px;
      padding-bottom: 20px;
    }

    a.big-button {
      margin: 0 165px 20px;
      line-height: 50px;
      color: white;
      text-align: center;
      text-decoration: none;
      background: #304090;
      border-radius: 4px;
      padding: 0 20px;
      display: block;
      font-weight: bolder;
      width: 240px;
      font-size: 18px;
    }

    .sub h1, .sub h2 {
      color: #304090;
      font-weight: bolder;
    }
  </style>
  <div class="sub">
    <h1>ДОБРО ПОЖАЛОВАТЬ В ЭКСТРАНЕТ ROOMP</h1>
    <p>Дорогой партнер,</p>
    <p>Рады сообщить, что подготовили для вас новый Экстранет Roomp, который уже доступен по <a
        href="https://extranet.roomp.co/?utm_source=email&utm_medium=email&utm_campaign=welcome-to-extranet">ссылке</a>.
      В этом письме мы коротко рассказали о функциях нашего Экстранета. Пожалуйста, прочитайте его.</p>
    <h2>КАЛЕНДАРЬ (CALENDAR)</h2>

    <img width="570" src="{{url('/img/special/subscription/calendar.gif') }}" />

    <p>Если вы работаете без Channel Manager, используйте <b>Календарь</b> для выставления доступности, цен, ограничений и
      специальных тарифов.</p>
    <p>Если вы работаете с нами через Channel Manager, с помощью <b>Календаря</b> вы можете убедиться, что доступность,
      цены и тарифы в нашей системе в точности повторяют доступность, цены и тарифы, которые вы выставили у себя.</p>
    <h2>БРОНИРОВАНИЯ (RESERVATIONS)</h2>
    <p>Не ищите подтверждение бронирования гостя Roomp в почтовом ящике.</p>
    <img style="width: 570px;" width="570" src="{{url('/img/special/subscription/reservations.gif') }}" />
    <p>Теперь все будущие заезды находятся во вкладке <b>Бронирования</b> — странице Экстранета Roomp,
      на которой можно увидеть, когда приедут ближайшие гости, сколько составит стоимость проживания и актуальный статус
      бронирования.</p>
    <h2>СВЕРКА (RECONCILIATION)</h2>
    <p>Во вкладке <b>Сверка</b> указаны все совершенные бронирования и общая сумма.</p>
    <img style="width: 570px;" width="570" src="{{url('/img/special/subscription/reconciliation.gif') }}" />
    <p>В <b>Сверке</b> также можно увидеть, сколько денег с гостя зарабатывает отель-партнер, а сколько получает Roomp.
    </p>
    <h2>ФИНАНСЫ ОТЕЛЯ (FINANCE)</h2>
    <img style="width: 570px;" width="570" src="{{url('/img/special/subscription/finances.png') }}" />
    <p>Во вкладке <b>Финансы</b> представлены все закрывающие документы для отеля.
      Больше не нужно искать документы в почте для бухгалтерии — все финансовые документы под контролем и в одном
      месте.</p>
    <h2>ОНЛАЙН-ЧАТ С ТЕХПОДДЕРЖКОЙ</h2>
    <img src="{{url('/img/special/subscription/chat.gif') }}" />
    <p>Звонить партнеру бывает не всегда удобно.
      Поэтому мы добавили онлайн-чат с техподдержкой отелей-партнеров Roomp, чтобы помогать вам не только по телефону,
      но
      и в онлайн-режиме.
      Обращайтесь к агентам поддержки Roomp по вопросам, связанным с условиями сотрудничества, гостями, бронированиями,
      финансами.</p>
    <h2>ВСЕ ДОКУМЕНТЫ В ОДНОМ МЕСТЕ</h2>
    <img src="{{url('/img/special/subscription/docs.gif') }}" />
    <p>Все нужные документы под рукой.
      Чтобы изучить оферту, инструкции или прочитать и распечатать памятку по обслуживанию гостей Roomp,
      нажмите на название вашего отеля в правом верхнем углу Экстранета.</p>
    <h2>ПРОСТАВЛЯЙТЕ ЗАЕЗДЫ ОДНИМ КЛИКОМ</h2>
    <img width="570" src="{{url('/img/special/subscription/booking_confirmation.png') }}" />
    <p>Гость от Roomp приехал в отель? Сообщите о заезде одним кликом! Чтобы проставить заезды, нажмите на название своего отеля в правом верхнем углу Экстранета, выберите вкладку «Подтверждение заездов», отметьте заезд или незаезд одним кликом.</p>
    <h2>ИЗМЕНЯЙТЕ БАНКОВСКИЕ РЕКВИЗИТЫ И НАЗНАЧАЙТЕ ОТВЕТСТВЕННЫХ</h2>
    <img src="{{url('/img/special/subscription/create_new_contact.png') }}" />
    <p>Поменяли реквизиты или наняли новых сотрудников? Обновите данные самостоятельно. В разделе «Организация» изменяйте реквизиты и добавляйте новых ответственных лиц, которые будут общаться с командой Roomp. </p>

    <p>Сервис работает на русском и английском языках.</p>
    <p>Откройте все возможности Экстранета Roomp для вашего отеля.</p>
    <table align="center" style="width: 570px;" width="570">
      <tr>
        <td style="vertical-align: middle" align="center" height="60" width="240">
          <a style="line-height: 50px; mso-line-height-rule: exactly;" class="big-button"
             href="https://extranet.roomp.co/?utm_source=email&utm_medium=email&utm_campaign=welcome-to-extranet">
            Перейти в Экстранет</a>
        </td>
      </tr>
    </table>

    <p>P.S. Мы постоянно развиваем наши продукты, и потому рады любой обратной связи, пожеланиям и предложениям. Пожалуйста,
      пишите комментарии по работе Экстранета в онлайн-чат.</p>

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
      <p><b>partners@roomp.co</b></p>

      <p style="float: right;">Команда <b>Roomp</b></p>
    </div>
  </div>
@endcomponent
