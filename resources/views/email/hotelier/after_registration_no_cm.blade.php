@component('email.reservation.layout')
  <div class='reminder'>
    <div class='greeting'>
      <p>Поздравляем! </p>
      <p>Вы стали партнёром сети отелей <b>Roomp</b>.</p>
      <p>Что будет происходить дальше?</p>
      <ol>
        <li>Мы позвоним и выберем удобный для Вас день, чтобы сфотографировать номера.</li>
        <li>Приедем и завезем брендированную продукцию Roomp. </li>
        <li>Заполним всю информацию о Вашем отеле и разместим на каналах продаж. </li>
      </ol>
      <p>Также во вложении Вы найдете памятку по обслуживанию гостей Roomp. Мы рекомендуем распечатать памятку и разместить ее в удобном для администратора месте. В ней перечислены действия администратора в том или ином случае, а также указаны необходимые контакты.</p>
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
        <p>Если у вас остались вопросы, пожалуйста, ответьте на это письмо.</p>
        <p>Ждем вашего подключения и добро пожаловать в сеть отелей Roomp!</p>
        <p><b>8 (800) 777 17 56</b></p>
        <p><b>partners@roomp.co</b></p>
        <p style="float: right; clear: both;">С уважением,</p>
        <p style="float: right; clear: both;">Команда <b>Roomp</b></p>

      </div>
    </div>
  </div>
@endcomponent
