<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
        .header {
            position: relative;
            width: 100%;
            padding-top: 20px;
            margin-bottom: 20px;
            text-align: center;
        }

        .logo {
            position: absolute;
            left: 0;
            margin-top: -10px;
            /* top: 50%;
            transform: translateY(-50%); */
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
            font-family: sans-serif, 'DejaVu Sans';
        }
    </style>

</head>

<body>
    <div style="padding: 30px;">
        <div class="header">
            <div class="logo">
                <img src="{{ public_path('pdf_logo.jpg') }}" style="height: 80px;" alt="Logo">
            </div>
            <div class="text-content">
                <h5 style="font-weight: 300; margin-bottom: 2px;">Republic of The Philippines</h5>
                <h5 style="margin-bottom: 2px;">CITY GOVERNMENT OF DAVAO</h5>
                <h4 style="margin-bottom: 2px;">OFFICE OF THE CITY ECONOMIC ENTERPRISE</h4>
                <h3 style="margin-bottom: 2px;">ORDER OF PAYMENT</h3>
            </div>
        </div>

        <div class="content">
            <div class="text">
                <p>TO: The City Treasurer</p>
                <br />
                <p style="white-space: nowrap;">Please accept payment for fees specified hereunder:</p>
                <br />
                <p>Name: <strong>{{ ucwords(strtolower($owner_name)) }}</strong></p>
                <p>Stall Description: <span>{{ ucwords(strtolower($owner_address)) }}</span></p>
            </div>
            <div class="text2">
                <div style="padding-left: 90px;">
                    <br />
                    <p>OP No.: <strong>{{ $op_number }}</strong></p>
                    <p>OP Sys. ID: {{ $op_sys }}</p>
                    <p>OP Date: {{ $op_date }}</p>
                    <p>Acct Ref. ID: {{ $owner_id }}</p>
                </div>
            </div>
        </div>

        <table style="width: 100%; border-collapse: collapse; font-size: 14px; margin-top: 10px;">
            <thead>
                <tr>
                    <th style="border: 1px solid black; padding: 5px; text-align: left;">#</th>
                    <th style="border: 1px solid black; padding: 5px; text-align: left;">Account Description</th>
                    <th style="border: 1px solid black; padding: 5px; text-align: center;">Account Code</th>
                    <th style="border: 1px solid black; padding: 5px; text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($account_code_details as $index => $item)
                    <tr>
                        <td style="padding: 5px;">{{ $loop->iteration }}.</td>
                        <td style="padding: 5px;">{{ $item['description'] }}</td>
                        <td style="padding: 5px; text-align: center;">
                            {{ $item['accountcode'] }}
                        </td>
                        <td style="padding: 5px; text-align: right;">
                            {{ number_format($item['amount'], 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <hr>
        <table style="width: 100%; margin-bottom: 20px; font-size: 14px;">
            <tr>
                <td style="padding: 5px; width: 50%;">
                    This order of payment is valid until {{ $valid_until_date ?? 'DATE HERE' }}
                </td>
                <td style="padding: 5px; text-align: right;">
                    <strong style="font-size: 16px;">TOTAL:
                        <span style="font-weight: bold; font-size: 16px;">
                            ₱{{ number_format($total_amount, 2) }}
                        </span>
                </td>
            </tr>
        </table>

        {{-- <table style="width: 100%; text-align: center; font-size: 14px;">
            <tr>
                <td style="padding: 10px;">Prepared by:</td>
                <td style="padding: 10px;">Noted by:</td>
            </tr>
            <tr>
                <td style="padding: 10px;">
                    <p style="margin: 4px 0 0 0; font-weight: 600;">{{ ucwords(strtolower($post_by)) }}</p>
                </td>
                <td style="padding: 10px;">
                    <div style="width: 80%; border-bottom: 1px solid black; margin: 0 auto;"></div>
                    <p style="margin: 4px 0 0 0; font-weight: 600;">ENP. ESTELA D. MALATE</p>
                    <p style="margin: 0;">OFFICER-IN-CHARGE</p>
                </td>
            </tr>
        </table> --}}
        <table width="100%" style="font-size: 13px; margin-top: 20px;">
            <tr valign="top">
                <!-- Left side -->
                <td width="50%">
                <p><strong>Assessed by:</strong> {{ ucwords(strtolower($post_by)) }}</p>
                <p><strong>Prepared by:</strong> {{ ucwords(strtolower($post_by)) }}</p>
                <br>
                <p><em>Note:</em> Please use the OPTN when paying online.</p>
                </td>

                <!-- Right side -->
                <td width="50%" style="padding-left: 40px;">
                <p><strong>Noted by:</strong> {{ ucwords(strtolower($signatory)) }}</p>
                <p><strong>Printed by:</strong> {{ ucwords(strtolower($post_by)) }}</p>
                </td>
            </tr>
        </table>
        <div
            style="border: 1px solid black; padding-top: 5px; padding-bottom: 5px; padding-left: 5px; display: inline-block; width: 100%; box-sizing: border-box; border-bottom: 0px;">
            <p style="margin: 0; font-size: 12px;">Steps when paying thru internet with <span style="font-weight: 600;">
                    LANDBANK's
                    Link.BizPortal:</span></p>
            <p style="margin: 0; font-size: 12px;">* Enrollment of your bank account via registration facility of
                selected payment option
                is
                required.</p>
        </div>
        <div class="footer"
            style="border: 1px solid black; padding-top: 5px; padding-bottom: 5px; padding-left: 5px; display: inline-block; width: 100%; box-sizing: border-box;">
            <div
                style="display: inline-block; width: 55%; vertical-align: top; padding-right: 30px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                <p style="margin: 0; font-size: 11px; margin-bottom: 2px; padding-top: 4px;">(1) Go to
                    https://www.lbp-eservices.com/egps/portal/index.jsp.
                    Click [Pay Now]</p>
                <p style="margin: 0; font-size: 11px; padding-top: 4px;">(2) Select merchant "City Government of
                    Davao".
                    Click [Continue]
                </p>
                <p style="margin: 0; font-size: 11px; padding-top: 4px;">(3) Select transaction type "Miscellaneous
                    Fees"</p>
                <p style="margin: 0; font-size: 11px; padding-top: 4px;">(4) Select payment gateway option
                    "Landbank/Bancnet/G-Cash"</p>
            </div>
            <div
                style="display: inline-block; width: 35%; vertical-align: top; overflow: hidden; text-overflow: ellipsis;">
                <p style="margin: 0; font-size: 11px; padding-top: 4px;">(5) Enter number/OPTN: <span
                        style="padding-left: 5px;">M{{ str_replace('-', '', $op_number) }}</span>
                </p>
                <p style="margin: 0; font-size: 11px; padding-top: 4px;">(6) Enter your contact info (mobile number or
                    email)</p>
                <p style="margin: 0; font-size: 11px; padding-top: 4px;">(7) Enter the generated security code</p>
                <p style="margin: 0; font-size: 11px; padding-top: 4px;">(8) Click [Submit] and wait for confirmation
                </p>
            </div>
        </div>
    </div>
</body>

</html
