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

    .act-number {
      width: 100%;
      text-align: center;
      border-bottom: 1px solid black;
      padding-bottom: 10px;
      font-weight: bold;
    }

    .role-of-the-company {
      padding-top: 20px;
      width: 100%;

    }
    .role-of-the-company :first-child {
      float: left;
      width: 110px;
    }
    .role-of-the-company :nth-child(2) {
      font-weight: bold;
    }

    table {
      border-collapse: collapse;
      width: 100%;
      padding-top: 40px;
      margin-bottom: 20px;

    }

    table, th, td {
      border: 1px solid black;
      font-weight: normal;
    }

    table :first-child {
      text-align: center;
    }

    .final-sum-string {
      width: 100%;
      border-bottom: 2px solid black;
      padding-bottom: 10px;
      padding-top: 40px;
    }

    .requisities {
      font-size: 12px;
      text-align: left;
      border: none;
    }

    .requisities table, th, td {
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
      border-bottom: 1px solid black;
      padding-left: 5px;
      padding-right: 5px;
      padding-bottom: 2px;
      padding-top: 10px;
    }


  </style>
</head>
<body>

<div class="container">
  @yield('content')
</div>
</body>
</html>
