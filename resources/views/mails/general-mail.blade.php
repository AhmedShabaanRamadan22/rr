<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns:v="urn:schemas-microsoft-com:vml" lang="ar">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;" />
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <title>منصة ركايا لجودة التشغيل</title>
    <style type="text/css">
        body {
            width: 100%;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
            direction: rtl;
            -webkit-font-smoothing: antialiased;
            font-family: 'IBM Plex Sans Arabic', 'Cairo', 'Segoe UI', Tahoma, Arial, sans-serif !important;
        }

        table {
            font-size: 14px;
            border: 0;
            font-family: 'IBM Plex Sans Arabic', 'Cairo', 'Segoe UI', Tahoma, Arial, sans-serif !important;
        }

        @media only screen and (max-width: 640px) {
            .main-header {
                font-size: 20px !important;
            }

            .container590 {
                width: 440px !important;
            }

            .main-button {
                width: 220px !important;
            }
        }

        @media only screen and (max-width: 479px) {
            .main-header {
                font-size: 18px !important;
            }

            .container590 {
                width: 280px !important;
            }
        }
    </style>
</head>

<body style="direction: rtl;">
    @component('mails.components.header', ['organization_logo' => $organization->logo])
    @endcomponent

    {!!$content!!}

    @component('mails.components.footer')
    @endcomponent

</body>

</html>