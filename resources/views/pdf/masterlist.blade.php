<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
        .header {
            width: 80%;
            margin: 0 auto; /* horizontally centers the block */
            padding-top: 20px;
            margin-bottom: 20px;
            text-align: center;
        }

        .logo,
        .text-content {
            display: inline-block;
            vertical-align: top;
        }

        .logo {
            width: 40px;
            padding-right: 20px;
        }

        .text-content {
            padding-left: 20px;
        }

        h5,
        h4,
        h3 {
            margin: 0;
        }

        h5 {
            font-size: 12px;
        }

        h4 {
            font-size: 14px;
        }

        h3 {
            font-size: 16px;
        }

        .content {
            width: 100%;
            display: inline-block;
        }

        .text,
        .text2 {
            display: inline-block;
            vertical-align: top;
        }

        .text {
            width: 54%;
            padding-right: 10px;
        }

        .text2 {
            width: 43%;
        }

        p {
            margin: 0;
            font-size: 14px;
        }

        /* .footer {
            font-size: 12px;
        } */

        body {
            font-family: sans-serif;
        }
        .td-style {
            padding: 5px;
            text-align: center;
            border-bottom: 1px solid black; /* This works in Dompdf */
        }
    </style>

</head>

<body>
    <div style="padding: 30px;">
        <div class="header">
            <div class="text-content">
                <h5 style="font-weight: 300; margin-bottom: 2px;">Republic of The Philippines</h5>
                <h5 style="margin-bottom: 2px;">CITY GOVERNMENT OF DAVAO</h5>
                <h4 style="margin-bottom: 2px;">OFFICE OF THE CITY ECONOMIC ENTERPRISE</h4>
                <h3 style="margin-bottom: 2px;">MASTERLIST OF COLD STORAGE STALL OWNERS </h3>
                <h4 style="margin-bottom: 2px;">{{$marketName->fieldDescription}} PUBLIC MARKET </h4>
            </div>
        </div>
        <table style="width: 100%; border-collapse: collapse; font-size: 14px; margin-top: 10px;">
            <thead>
                <tr>
                    <th style="border: 1px solid black; padding: 5px; text-align: center;">#</th>
                    <th style="border: 1px solid black; padding: 5px; text-align: center;">OWNER NAME</th>
                    <th style="border: 1px solid black; padding: 5px; text-align: center;">OWNER ID Code</th>
                    <th style="border: 1px solid black; padding: 5px; text-align: center;">STALL
                    NO.</th>
                    <th style="border: 1px solid black; padding: 5px; text-align: center;">AREA/SQ.
METER</th>
                    <th style="border: 1px solid black; padding: 5px; text-align: center;">STALL AREA EXT.</th>
                    <th style="border: 1px solid black; padding: 5px; text-align: center;">LOCATION OF INFLUENCE </th>
                    <th style="border: 1px solid black; padding: 5px; text-align: center;">RENTAL PER DAY</th>
                    <th style="border: 1px solid black; padding: 5px; text-align: center;">BUS. ID</th>
                    <th style="border: 1px solid black; padding: 5px; text-align: center;">LINE OF BUSINESS</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($awardees as $index => $awardee)
                    <tr class="td-style">
                        <td style="padding: 5px; text-align: center;">{{ $loop->iteration }}.</td>
                        <td style="padding: 5px; text-align: center;">{{ $awardee['full_name'] }}</td>
                        <td style="padding: 5px; text-align: center;">{{ $awardee['ownerId'] }}</td>
                        <td style="padding: 5px; text-align: center;">{{ $awardee['stallNoId'] }}</td>
                        <td style="padding: 5px; text-align: center;">{{ number_format($awardee['stallArea']) }}</td>
                        <td style="padding: 5px; text-align: center;">{{ number_format($awardee['StallAreaExt']) }}</td>
                        <td style="padding: 5px; text-align: center;">{{ ($awardee['CFSI']) ? $awardee['CFSI'] : "COMMON STALL" }}</td>
                        <td style="padding: 5px; text-align: center;">{{ number_format($awardee['ratePerDay']) }}</td>
                        <td style="padding: 5px; text-align: center;">{{ $awardee['busID'] }}</td>
                        <td style="padding: 5px; text-align: center;">{{ $awardee['lineOfBusiness'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html
