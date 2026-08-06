<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rappel de candidature - Next</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f5f5f5;
            font-family: Arial, Helvetica, sans-serif;
        }
        .container {
            background: #f5f5f5;
            padding: 40px 0;
        }
        .card {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
        }
        .header {
            background: #6b0f2b;
            padding: 40px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 52px;
            font-weight: bold;
            color: #ffffff;
            letter-spacing: 2px;
        }
        .header p {
            margin: 10px 0 0;
            color: #f2d8df;
            font-size: 18px;
        }
        .content {
            padding: 45px;
        }
        .emoji {
            font-size: 28px;
            text-align: center;
            margin: 0;
        }
        .divider {
            width: 80px;
            border: none;
            border-top: 2px solid #6b0f2b;
            margin: 15px auto 35px;
        }
        .text {
            font-size: 19px;
            color: #333;
            line-height: 1.8;
            margin-top: 0;
        }
        .info-box {
            background: #faf7f8;
            border-radius: 10px;
            padding: 25px;
            margin: 35px 0;
        }
        .info-box p {
            margin: 12px 0;
            font-size: 17px;
        }
        .days-left {
            font-size: 22px;
            font-weight: bold;
            color: #6b0f2b;
            text-align: center;
        }
        .btn {
            display: inline-block;
            background: #6b0f2b;
            color: #ffffff;
            text-decoration: none;
            padding: 16px 40px;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
        }
        .footer {
            padding: 30px;
            background: #fafafa;
            text-align: center;
        }
        .footer hr {
            border: none;
            border-top: 1px solid #dddddd;
        }
        .footer p {
            font-size: 14px;
            color: #777;
            line-height: 1.8;
            margin-top: 25px;
        }
        .footer .copy {
            font-size: 13px;
            color: #999;
            margin-top: 25px;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" class="container">
        <tr>
            <td align="center">

                <table role="presentation" width="600" cellpadding="0" cellspacing="0" class="card">

                    <!-- HEADER -->
                    <tr>
                        <td class="header">
                            <h1>NEXT</h1>
                            <p>Trouvez la bourse qui vous correspond</p>
                        </td>
                    </tr>

                    <!-- CONTENU -->
                    <tr>
                        <td class="content">

                            <p class="emoji">🔔</p>

                            <hr class="divider">

                            <p class="text">
                                Ceci est un rappel concernant une bourse que vous avez enregistrée dans vos favoris.
                            </p>

                            <p class="text">
                                La date limite de candidature approche. Retrouvez ci-dessous les informations de la bourse.
                            </p>

                            <div class="info-box">

                                <p>
                                    <strong>Bourse :</strong> {{ $scholarship->title }}
                                </p>

                                @if($scholarship->university)
                                <p>
                                    <strong>Université :</strong> {{ $scholarship->university }}
                                </p>
                                @endif

                                @if($scholarship->country)
                                <p>
                                    <strong>Pays :</strong> {{ $scholarship->country }}
                                </p>
                                @endif

                                @if($scholarship->deadline)
                                <p>
                                    <strong>Date limite :</strong>
                                    {{ \Carbon\Carbon::parse($scholarship->deadline)->format('d/m/Y') }}
                                </p>
                                @endif

                            </div>

                            <p class="days-left">
                                Plus que {{ $daysLeft }} {{ $daysLeft > 1 ? 'jours' : 'jour' }}
                            </p>

                            <p class="text" style="text-align:center;">
                                Ne manquez pas cette opportunité. Finalisez votre candidature avant la date limite.
                            </p>

                            <div class="text-center" style="margin:40px 0;">

                                <a href="{{ env('FRONTEND_URL') }}/details/{{ $scholarship->id }}"
                                   class="btn"
                                   style="
                                        display:inline-block;
                                        background:#6b0f2b;
                                        color:#ffffff;
                                        text-decoration:none;
                                        padding:16px 40px;
                                        border-radius:8px;
                                        font-size:18px;
                                        font-weight:bold;">
                                    Voir la bourse
                                </a>

                            </div>

                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td class="footer">

                            <hr>

                            <p>
                                Cet email a été envoyé automatiquement par <strong>NEXT</strong>.<br>
                                Merci de ne pas y répondre.
                            </p>

                            <p class="copy">
                                &copy; {{ date('Y') }} NEXT. Tous droits réservés.
                            </p>

                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>