<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            font-family: "IBM Plex Sans Arabic";
            margin: 0;
            padding: 0;
            background-color: rgb(255, 255, 255);
        }

        header {
            border-bottom: 0.5px solid;
        }

        footer {
            text-align: center;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            border-top: 1.5px solid #000000;
        }

        .body-table {
            text-align: center;
            width: 60%;
            margin-left: auto;
            margin-right: auto;
            margin-top: 20px;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .body-table th,
        .body-table td {
            padding: 15px;
            border: 0.01% solid #000000;
        }

        .body-table th {
            background-color: {{ $data['organization_data']->primary_color ?? '#CAB272' }};
        }


        .space-top {
            padding-top: 1.5%;
        }

        .hr-space {
            padding: 3% 0%
        }

        .note {
            margin: 10px 0;
            padding-left: 20px;
            position: relative;
            border-left: 4px solid {{ $data['organization_data']->primary_color ?? '#cab272cc' }};
        }

        .notes-area {
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-height: 100vh;
        }

        .notes-group {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-around;
            page-break-inside: avoid;
        }

        .note::before {
            content: '';
            border-radius: 50%;
            width: 12px;
            height: 12px;
            position: absolute;
            left: -10px;
            top: 5px;
        }

        .note-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin: 0 0 5px 0;
        }

        .note-content {
            font-size: 14px;
            color: #555;
            margin: 0;
        }

        .note-metadata {
            font-size: 12px;
            color: #999;
            margin-top: 5px;
        }

        .table-container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            text-align: center;
        }

        .notes-table td{
            border: none;
            padding: 2px 0;
            font-size: 12px;
            color: #999;
        }

        .barcode-table th,
        .barcode-table td {
            padding: 10px;
            border: none;
        }

        .assist-table td{
            border:none;
            font-size:12px;
            padding: 3px;
            text-align:start;
            width: 100%
        }

        .content-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .content-table td {
            border: 1px solid;
            padding: 10px;
            text-align: center;
            vertical-align: middle;
            border-color: {{ $data['organization_data']->primary_color ?? '#cab272cc' }};

        }

        .first-column {
            width: 30%;
        }

        .second-column-top,
        .second-column-bottom {
            height: 150px;
        }

        .image-size {
            width: 750px;
            height: 250px;
            background-repeat: no-repeat;
            background-position: center center;
            background-size: 150px;
            border: 1px solid black;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>

<body>
    <div style="margin:0 20;">
        {!! $content !!}
    </div>
</body>

</html>
