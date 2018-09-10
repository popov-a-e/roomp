<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <style>
    body, html {
      margin: 0;
      padding: 40px;
      box-sizing: border-box;
      font-size: 14px;
    }

    * {
      box-sizing: border-box;
      font-family: dejavu sans;
    }

    .roomp-logo {
      height: 140px;
    }

    .act-number {
      width: 100%;
      text-align: center;
      border-bottom: 1px solid black;
      padding-bottom: 10px;
      font-weight: bold;
    }

    .role-of-the-company {
      padding-top: 10px;
      width: 100%;

    }

    .role-of-the-company :first-child {
      float: left;
      width: 110px;
    }

    .role-of-the-company :nth-child(2) {
      font-weight: bold;
    }

    table.price {
      border-collapse: collapse;
      width: 100%;
      padding-top: 30px;
      margin-bottom: 20px;
      border: 1px solid black;
    }

    table.price :first-child {
      text-align: center;
    }

    table.money-calculation td {
      border: 1px solid black;
      padding: 5px;
    }

    .price tr, .price td {
      border: 1px solid black;
    }

    .final-sum-string {
      width: 100%;
      border-bottom: 2px solid black;
      padding-bottom: 10px;
      padding-top: 40px;
      margin-bottom: 40px;
    }

    .requisities {
      font-size: 12px;
      text-align: left;
      border: none;
    }

    .requisities {
      border: none;
    }

    .requisities tr {
      text-align: left;
    }

    .requisities-name {
      width: 85px;
      border: none;
    }

    .requisities-value {
      width: 85px;
      border: none;
    }

    .inn {
      border: none;
      border-bottom: 1px solid black;
      padding-left: 5px;
      padding-right: 5px;
      padding-bottom: 2px;
      padding-top: 10px;
    }

    p {
      text-indent: 20px;
    }

    .ceo {
      width: 49%;
    }

    table.signatures {
      width: 100%;
    }
  </style>
</head>
<body>
<div class="container">
  <div class="act-netting">
    <div class="roomp-logo" style="float: right">
      <img style="width: 200px; padding-top: 40px" src="{{ base_path('public/img/logo.png')}}" />
    </div>

    <h2 style="clear: both; width: 100%; text-align: center; font-size: 20px;">Акт взаиморасчета №R{{ $hotelID }}-{{$month}}/{{$year}}</h2>

    <table style="width: 100%; padding-bottom: 20px" cellpadding="0" cellspacing="0">
      <tr>
        <td style="text-align: left">г. {{ $hotel->city->ru }}</td>
        <td style="text-align: right">{{ date('d') }} {{ __('dates.dec')[1 * date('m', strtotime("$year-$month-01 + 1 month")) - 1] }} {{ $year }} г.</td>
      </tr>
    </table>

    <p class="indent-size">
      {{ $organization->form }} {{ $organization->name }}, в дальнейшем именуемое
      «Принципал», в лице {{ $inflectedCEO }}, действующе{{ $gender === 'm' ? 'го' : 'й' }} на основании Устава, с одной
      стороны, и ООО «РУМП», в дальнейшем именуемое «Агент», в лице Урманшина Эмиля
      Ильдаровича, действующего на основании Устава, с другой стороны, именуемые в дальнейшем
      «Стороны», составили настоящий акт зачёта взаимных требований (далее - "Акт") по агентскому
      договору №{{ $organization->contract_n }} от «{{ date('d', strtotime($organization->contract_date)) }}» {{ __('dates.dec')[1 * date('m', strtotime($organization->contract_date)) - 1] }} {{ date('Y', strtotime($organization->contract_date)) }} г. по состоянию на «{{ date('d') }}» {{ __('dates.dec')[1 * date('m', strtotime("$year-$month-01 + 1 month")) - 1] }} {{ $year }} г.
    </p>

    <table class="money-calculation" cellpadding="0" cellspacing="0">
      <tr>
        <td></td>
        <td style="font-weight: bold">Агент</td>
        <td style="font-weight: bold">Принципал</td>
      </tr>

      <tr>
        <td>Кредиторская
          задолженность
        </td>
        <td>{{ $credit }} рублей за услуги по
          проживанию, без НДС
        </td>
        <td>{{ $debit }} рублей по оплате агентского
          вознаграждения, без НДС
        </td>
      </tr>

      <tr>
        <td>Итог после взаимозачета:</td>
        <td>{{ $credit - $debit > 0 ? ($credit - $debit) . ' р.' : '' }}</td>
        <td>{{ $debit - $credit > 0 ? ($debit - $credit) . ' р.' : '' }}</td>
      </tr>

    </table>

    <p class="indent-size">
      Стороны согласились произвести взаимозачёт по вышеупомянутому договору в сумме {{ abs($credit - $debit) }} рублей ({{ sum_to_russian_string(abs($credit - $debit)) }}).
    </p>

    <p class="indent-size">
      Незачтённая сумма в размере {{ abs($credit - $debit) }} рублей ({{ sum_to_russian_string(abs($credit - $debit)) }})
      перечисляется дебитором в пользу кредитора на расчётный счёт последнего в течение 5 дней с даты
      подписания настоящего Акта.
    </p>

    <div style="float: left; position: relative;">
      @include('base64.signature', ['style' => 'width: 95px; top: 10px; left: 0px; position: absolute;'])
      @include('base64.stamp_old', ['style' => 'width: 300px; top: -30px; left: -20px; position: absolute;'])

      <table class="signatures" cellpadding="0" cellspacing="0">
        <tr>
          <td style="font-weight: bold; width: 50%; ">Агент:</td>
          <td style="padding-left: 60px; width: 50%; font-weight: bold">Принципал:</td>
        </tr>

        <tr>
          <td style="position: relative; padding-top: 20px; width: 50%; ">___________________/ Урманшин Э.И. /
          </td>
          <td style="padding-left: 60px; padding-top: 20px; width: 50%; ">___________________/ {{ $organization->CEO_short_name }}/</td>
        </tr>
      </table>
    </div>

  </div>

</div>
</body>
</html>