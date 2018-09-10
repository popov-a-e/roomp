@component('email.reservation.layout')
<div class='reminder'>
  <div class='greeting'>
    <p>Уважаемый партнер,</p>
    <p>Спасибо за то, что вы с нами!</p>
    <p>У нас есть для Вас несколько нововведений, которые сделают нашу совместную работу еще проще:</p>
    <ol>
      <li>У нас новые реквизиты (во вложении).</li>
      <li>Мы перешли на электронный документооборот
        <ul style="padding-left: 10px;">
          <p style="margin: 10px 0 0 0"><b>Что это значит?</b></p>
          <li>Теперь вы принимаете электронную оферту</li>
          <li>В конце месяца в экстранете проводится сверка</li>
          <li>На основе сверки автоматически генерируются все закрывающие документы в электронном виде</li>
        </ul>
      </li>
    </ol>
    <p>Пожалуйста, зарегистрируйтесь по <a href='{{ "https://$subdomain.roomp.co/register?token=$token"}}'>ссылкe</a> в течение 3-х дней после получения письма.</p>
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