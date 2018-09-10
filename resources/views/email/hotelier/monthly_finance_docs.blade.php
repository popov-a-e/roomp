@component('email.reservation.layout')
  <div class='reminder'>
    <div class='greeting'>
      <p>Уважаемый партнер!</p>
      <p>Мы хотим поблагодарить Вас за еще один месяц совместной работы. В приложении к данному письму вы найдете следующие документы за {{ strtolower(__('dates.full')[date('m', strtotime("$year-$month-01")) - 1])  }}:</p>
      <ul>
        <li>Отчет агента</li>
        <li>Акт об оказании услуг</li>
        <li>Акт взаиморасчета</li>
        @if ($bill) <li>Счет</li> @endif
      </ul>
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
      @if ($bill)
        <p>Просим Вас ознакомиться с документами и оплатить счет <b><u>до 12 {{ __('dates.dec')[date('m', strtotime("$year-$month-01 + 1 month")) - 1]  }}</u></b>.</p>
      @else
        <p>Данным письмом мы подтверждаем, что до 15 {{ __('dates.dec')[date('m', strtotime("$year-$month-01 + 1 month")) - 1] }} произведем оплату по акту взаиморасчета. </p>
      @endif
      <p> Также напоминаем, что при необходимости, копии всех документов Вы можете найти у себя в личном кабинете. </p>
      <div class="footer">
        <p>Если у Вас остались вопросы, пожалуйста, обращайтесь к нам!</p>
        <p><b>8 (800) 777 17 56</b></p>
        <p><b>partners@roomp.co</b></p>

        <p style="float: right;">Команда <b>Roomp</b></p>
      </div>
    </div>
  </div>
@endcomponent
