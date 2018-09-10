<!DOCTYPE html>
<html>
<head>

  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <style>

    body, html {
      margin: 0;
      padding: 40px;
      box-sizing: border-box;
      font-size: 12px;

    }

    * {
      box-sizing: border-box;
      font-family: dejavu sans;
    }

    .invoice {
      position: relative;
    }

    table.bank {
      width: 100%;
      border: 1px solid black;
      border-collapse: collapse;
      font-size: 11px;
    }

    .bank tr {
      width: 100%;
    }

    .bank td {
      padding: 5px;
    }

    .bank tr, .bank td {
      border: 1px solid black;
    }

    td.border-bottom-none {
      border-bottom: 1px solid white;
    }

    h1 {
      width: 100%;
      text-align: center;
      font-size: 14px;
      padding: 20px 0px;
    }

    table.price {
      width: 100%;
      border: 1px solid black;
      border-collapse: collapse;
      margin-top: 20px;
    }

    .price tr {
      width: 100%;
    }

    .price td {
      padding: 5px;
    }

    .price tr, .price td {
      border: 1px solid black;
    }

    .final-sum-string {
      clear: both;
      border-bottom: 2px solid black;
      padding-bottom: 3px;
    }

    table.signa {
      width: 100%;
    }

    .centerd-text-table-td {
      text-align: center;
    }

    .place-to-sign {
      border-top: 1px solid black;
      font-size: 10px;
    }


  </style>
</head>
<body>

<div class="container">
  <div class="invoice">

    <img style="width: 200px; padding-top: 55px; float: right" src="{{ base_path('public/img/logo.png')}}" />

    <p style="padding-bottom: 10px">ООО "Румп"</p>

    <p>
      190103, г. Cанкт-Петербург, ул. Дровяная, д. 9, корп. 2,
      <br>литер “Ж”, помещение 2-Н <br>
      Телефон: 8-800-777-17-56<br>
      Эл.почта: credit@roomp.co<br>
      Сайт: https://roomp.co<br>
    </p>

    <p style="padding: 5px 0px; font-size: 10px">
      Образец заполнения платежного поручения
    </p>

    <table class="bank">
      <tr>
        <td style="width: 58%" class="border-bottom-none">АО "ТИНЬКОФФ БАНК"</td>
        <td style="width: 7%">БИК</td>
        <td style="width: 35%" class="border-bottom-none">044525974</td>
      </tr>

      <tr>
        <td style="width: 58%; padding-top: 20px; font-size: 10px">Банк получателя</td>
        <td style="width: 7%; padding-bottom: 20px">Сч. №</td>
        <td style="width: 35%; padding-bottom: 20px">30101810145250000974</td>
      </tr>

      <tr>
        <td style="width: 58%; position: relative">
          <div style="position: absolute; height: 10px; width: 1px; color: black"></div>

          <span style="">ИНН</span>
          <span style="padding-left: 20px; padding-right: 20px">7839097483</span>
          <span style="">КПП</span>
          <span style="">783901001</span>

        </td>

        <td style="width: 7%" class="border-bottom-none">Сч. №</td>
        <td style="width: 35%" class="border-bottom-none">40702810710000287067</td>
      </tr>

      <tr>
        <td style="width: 58%">ООО "Румп" <br> Получатель</td>
        <td style="width: 7%; border-top: none"></td>
        <td style="width: 35%; border-top: none"></td>

      </tr>

    </table>

    <h1>Счёт на оплату №B{{ $hotelID }}-{{$month}}/{{$year}} от {{date('d')}} {{ __('dates.dec')[1 * date('m', strtotime("$year-$month-01 + 1 month")) - 1] }} {{ $year }}</h1>

    <table>
      <tr>
        <td>Исполнитель:</td>
        <td>ООО "Румп"</td>
      </tr>

      <tr>
        <td>Заказчик:</td>
        <td>{{ $organization->form }} {{ $organization->name }}, ИНН {{ $organization->INN }}, КПП {{ $organization->KPP }}</td>
      </tr>

    </table>

    <table style="" class="price">

      <tr>
        <td>№</td>
        <td>Наименование работ, услуг</td>
        <td>Кол-во</td>
        <td>Ед.</td>
        <td>Цена</td>
        <td>Сумма</td>
      </tr>

      <tr>
        <td>1</td>
        <td>Услуги бронирования за {{ __('dates.full')[$month - 1] }} {{ $year }}</td>
        <td>1</td>
        <td></td>
        <td>{{ number_format($sum, 2, ',', ' ') }}</td>
        <td>{{ number_format($sum, 2, ',', ' ') }}</td>
      </tr>

    </table>

    <table style="float: right">

      <tr>
        <td style="text-align: right; font-weight: bold; padding-right: 20px">Итого к оплате:</td>
        <td style="font-weight: bold;">{{ number_format($sum, 2, ',', ' ') }}</td>
      </tr>

      <tr>
        <td style="text-align: right; padding-right: 20px">В том числе НДС:</td>
        <td>Без НДС</td>
      </tr>

    </table>

    <p class="final-sum-string">Всего к оплате: {{ sum_to_russian_string($sum) }}.</p>

    <p>Оплатить не позднее {{date('d.m.Y', strtotime("$year-$month-12 + 1 month")) }}</p>

    <p>Счет выставлен на основании Акта взаиморасчета №R{{ $hotelID }}-{{$month}}/{{$year}} от {{date('d')}} {{ __('dates.dec')[1 * date('m', strtotime("$year-$month-01 + 1 month")) - 1] }} {{ $year }}</p>

    <p>Оплата настоящего счета означает полное и безоговорочное согласие заказчика с условиями договора-оферты №{{ $offer->id }},
      размещенного в экстранете отеля.
    </p>

    <div style="float: left; position: relative">
      @include('base64.stamp', ['style' => 'width: 300px; top: -30px; left: 230px; position: absolute;'])
      @include('base64.signature', ['style' => 'width: 95px; top: -10px; left: 450px; position: absolute;'])
      @include('base64.signature', ['style' => 'width: 95px; top: 40px; left: 450px; position: absolute;'])

      <table class="signa">

      <tr>
        <td>Поставщик</td>
        <td class="centerd-text-table-td">Генеральный Директор</td>
        <td></td>
        <td class="centerd-text-table-td">Урманшин Эмиль<br>Ильдарович</td>
      </tr>



      <tr>
        <td></td>
        <td class="centerd-text-table-td place-to-sign" style="position: relative">должность
        </td>
        <td class="centerd-text-table-td place-to-sign" style="position: relative">подпись
        </td>
        <td class="centerd-text-table-td place-to-sign">расшифровка подписи</td>
      </tr>

      <tr>
        <td>Главный (старший) бухгалтер</td>
        <td></td>
        <td></td>
        <td></td>
      </tr>

      <tr>
        <td></td>
        <td></td>
        <td class="centerd-text-table-td place-to-sign" style="position: relative">подпись
        </td>
        <td class="centerd-text-table-td place-to-sign">расшифровка подписи</td>
      </tr>

    </table>
    </div>






  </div>
</div>
</body>
</html>
