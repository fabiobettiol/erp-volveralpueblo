<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style type="text/css">
        body {
            padding: 0 !important;
            margin: 0 !important;
            font-size: 8px;
        }

        .page-title {
            margin-bottom: 100px;
        }

        a {
            text-decoration: none;
        }

        .img {
            margin:0;
            padding: 0;
        }

        .row {
            margin: 0 !important;
            padding: 0 !important;
        }

        .logo {
            width: 80px;
            height: auto;
        }

        .icon {
            width: 30px;
            height: auto;
        }

        .logo-cdr {
            width: 50px;
            height: auto;
        }

        h1 {
            font-size: 15px !important;
        }

        table {
            border-spacing: 2px;
            border-collapse: separate;

        }

        td.border {
            border: 1px dotted #EFEFEF;
        }

        td.data {
            background-color: #EFEFEF;
            text-align: right;
        }

        td.text {
            background-color: #EFEFEF;
            text-align: left;
        }
    </style>

</head>
<body>
    <div class="row"><img class="logo" src="{{ public_path('storage/logos/logo-volver-al-pueblo.png') }}"></div>
        @yield('resourceTitle')
        @yield('content')
</body>
</html>

