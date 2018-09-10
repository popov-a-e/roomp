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

    .offer {
      /*
      max-width: 800px;
      margin-left: calc(50% - 400px);*/
    }

    .offer > table {
      width: 100%;
      table-layout: fixed;
    }

    table p {
      margin: 0;
      padding: 0;
    }

    .page {
      padding: 50px 0 50px 10px;

      /* height: 900px; */
      width: 800px;
      position: relative;
    }

    p.center, h2 {
      text-align: center;
      width: 100%;
    }

    .heading {

      font-size: 16px;
      padding: 10px 0px 10px 0px;
      text-transform: uppercase;
    }

    .subtitle {
      font-weight: bold;
      padding: 5px 0px 5px 0px;
    }

    .text {

    }

    .number {
      text-align: center;
      position: absolute;
      top: 950px;
      left: 334px;

    }

  </style>
</head>
<body>

<div class="container">
  @yield('content')
</div>
</body>
</html>
