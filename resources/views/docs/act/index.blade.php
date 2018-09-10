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

    .ceo {
      width: 49%;
    }


  </style>
</head>
<body>

<div class="container">
  <div class="act" style="position: relative">

    <div class="roomp-logo">
      <img style="width: 200px; padding-top: 40px" src="{{ base_path('public/img/logo.png')}}" />
    </div>

    <div class="act-number">Акт № A{{ $hotelID }}-{{date('m', strtotime("$year-$month-01"))}}/{{$year}} от {{ date('d') }} {{ __('dates.dec')[1 * date('m', strtotime("$year-$month-01 + 1 month")) - 1] }} {{ $year }}</div>

    <div class="role-of-the-company">
      <span>Агент:</span>
      <span>ООО "Румп"</span>
    </div>

    <div class="role-of-the-company">
      <span>Принципал:</span>
      <span>{{ $organization->form }} {{ $organization->name }}</span>
    </div>


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

    <div style="text-align: right; font-weight: bold">
      <span style="">Итого к оплате:</span>
      <span style="padding-left: 10px; width: 100px;">{{ number_format($sum, 2, ',', ' ') }}</span>
    </div>

    <div style="text-align: right">
      <span style="">В том числе НДС:</span>
      <span style="padding-left: 10px; width: 100px;">Без НДС</span>
    </div>

    <div class="final-sum-string">Общая стоимость выполненных работ, оказанных услуг: {{ sum_to_russian_string($sum) }}
    </div>

    <table style="width: 49%; float: left; border: none" class="requisities">
      <tr>
        <td class="requisities-name">Агент:</td>
        <td><span style="font-weight: bold; padding-left: 5px">ООО "Румп"</span></td>
      </tr>

      <tr>
        <td class="requisities-name">ИНН</td>
        <td>
          <span class="inn">7839097483</span>
          <span style="padding-left: 10px; padding-right: 10px">КПП</span>
          <span class="inn">783901001</span>
        </td>
      </tr>

      <tr style="padding-top: 20px">
        <td class="requisities-name">Адрес</td>
        <td class="inn"><span>190103, г. Cанкт-Петербург, ул. Дровяная, д. 9, корп. 2, литер “Ж”, помещение 2-Н</span></td>
      </tr>

      <tr>
        <td class="requisities-name">Р/с</td>
        <td class="inn"><span>40702810710000287067</span></td>
      </tr>

      <tr>
        <td class="requisities-name">К/с</td>
        <td class="inn"><span>30101810145250000974</span></td>
      </tr>

      <tr>
        <td class="requisities-name">Банк</td>
        <td class="inn"><span>АО "ТИНЬКОФФ БАНК"</span></td>
      </tr>

      <tr>
        <td class="requisities-name">БИК</td>
        <td class="inn"><span>044525974</span></td>
      </tr>

      <tr>
        <td class="requisities-name">Телефон</td>
        <td class="inn"><span>8-800-777-17-56</span></td>
      </tr>

    </table>

    <table style="width: 49%; float: right; border: none" class="requisities">

      <tr>
        <td class="requisities-name">Принципал:</td>
        <td><span style="font-weight: bold; padding-left: 5px">{{ $organization->form }} {{ $organization->name }}</span></td>
      </tr>

      <tr>
        <td class="requisities-name">ИНН</td>
        <td>
          <span class="inn">{{ $organization->INN }}</span>
          <span style="padding-left: 10px; padding-right: 10px">КПП</span>
          <span class="inn">{{ $organization->KPP }}</span>
        </td>
      </tr>

      <tr style="padding-top: 20px">
        <td class="requisities-name">Адрес</td>
        <td class="inn"><span>{{ $organization->legal_address }}</span></td>
      </tr>

      <tr>
        <td class="requisities-name">Р/с</td>
        <td class="inn"><span>{{ $organization->account }}</span></td>
      </tr>

      <tr>
        <td class="requisities-name">К/с</td>
        <td class="inn"><span>{{ $organization->corr_account }}</span></td>
      </tr>

      <tr>
        <td class="requisities-name">Банк</td>
        <td class="inn"><span>{{ $organization->bank }}</span></td>
      </tr>

      <tr>
        <td class="requisities-name">БИК</td>
        <td class="inn"><span>{{ $organization->BIC }}</span></td>
      </tr>

      <tr>
        <td class="requisities-name">Телефон</td>
        <td class="inn"><span>{{ $organization->phone }}</span></td>
      </tr>

    </table>

    <div style="width: 100%; clear: both; padding-top: 20px; position: relative;">

      @include('base64.signature', ['style' => 'width: 95px; top: 10px; left: 0px; position: absolute;'])
      @include('base64.stamp', ['style' => 'width: 300px; top: -30px; left: -20px; position: absolute;'])
      <table style="width: 47%; border: none; float: left; text-align: center">
        <tr>
          <td class="" style="width: 50px"></td>
          <td class="" style="width: 30px"><span></span></td>
          <td class="" style="font-size: 12px"><span>Генеральный директор</span></td>
        </tr>

        <tr>
          <td class="" style="width: 50px"></td>
          <td class="" style="width: 30px"><span>/</span></td>
          <td class="" style="padding: 5px 0px"><span>Урманшин Эмиль Ильдарович</span></td>
        </tr>

        <tr>
          <td class="" style="font-size: 12px; border-top: 1px solid black; width: 50px; min-width: 50px; max-width: 50px; position: relative; ">
            М.П.
          </td>
          <td class="" style="width: 30px"><span></span></td>
          <td class="" style="font-size: 12px; border-top: 1px solid black; position: relative">
            <span>расшифровка подписи</span>
          </td>
        </tr>
      </table>

      <table style="width: 47%; border: none; float: right; text-align: center">
        <tr>
          <td class="" style="width: 50px"></td>
          <td class="" style="width: 30px"><span></span></td>
          <td class="" style="font-size: 12px"><span>Генеральный директор</span></td>
        </tr>

        <tr>
          <td class="" style="width: 50px"></td>
          <td class="" style="width: 30px"><span>/</span></td>
          <td class="" style="padding: 5px 0px"><span>{{ $organization->CEO }}</span></td>
        </tr>

        <tr>
          <td class="" style="font-size: 12px; border-top: 1px solid black; width: 50px">М.П.</td>
          <td class="" style="width: 30px"><span></span></td>
          <td class="" style="font-size: 12px; border-top: 1px solid black"><span>расшифровка подписи</span></td>
        </tr>
      </table>

    </div>





  </div>
</div>
</body>
</html>
