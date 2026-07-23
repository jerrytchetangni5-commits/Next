@php
    $plural = $daysLeft > 1 ? 's' : '';
@endphp
<!DOCTYPE html>
<html lang="fr" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="format-detection" content="telephone=no, date=no, address=no, email=no">
<meta name="color-scheme" content="light">
<meta name="supported-color-schemes" content="light">
<meta name="x-apple-disable-message-reformatting">
<title>Rappel de date limite</title>
<!--[if mso]>
<noscript>
<xml>
<o:OfficeDocumentSettings>
<o:PixelsPerInch>96</o:PixelsPerInch>
</o:OfficeDocumentSettings>
</xml>
</noscript>
<![endif]-->
<style>
    body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
    table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
    body { margin: 0; padding: 0; width: 100% !important; background-color: #F5F1F0; }

    @media only screen and (max-width: 620px) {
        .stack-padding { padding-left: 24px !important; padding-right: 24px !important; }
        .header-padding { padding: 40px 24px 32px !important; }
        .medallion, .medallion td { width: 80px !important; height: 80px !important; }
        .medallion-number { font-size: 32px !important; }
    }
</style>
</head>
<body style="margin:0; padding:0; background-color:#F5F1F0;">

    <div style="display:none; max-height:0; overflow:hidden; mso-hide:all; font-size:1px; line-height:1px; color:#F5F1F0;">
        Plus que {{ $daysLeft }} jour{{ $plural }} pour candidater à {{ $scholarship->title }}.
        &nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;
    </div>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" bgcolor="#F5F1F0" style="background-color:#F5F1F0;">
        <tr>
            <td align="center" style="padding:48px 16px;">

                <table role="presentation" width="600" cellpadding="0" cellspacing="0" bgcolor="#ffffff" style="width:100%; max-width:600px; background-color:#ffffff; border-radius:14px; overflow:hidden; box-shadow:0 12px 32px rgba(74,15,24,0.14); font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

                    <tr>
                        <td align="center" class="header-padding" bgcolor="#4A0F18" style="background-color:#4A0F18; padding:44px 40px 40px;">

                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/next.png'))) }}" alt="Next" style="display:block; max-width:120px; margin:0 auto 20px; height:auto;">

                            <p style="margin:0 0 24px; color:rgba(255,255,255,0.72); font-size:12px; font-weight:600; letter-spacing:2px; text-transform:uppercase;">
                                Rappel de date limite
                            </p>

                            <table role="presentation" cellpadding="0" cellspacing="0" class="medallion" style="margin:0 auto;">
                                <tr>
                                    <td width="96" height="96" align="center" valign="middle" bgcolor="#ffffff" style="width:96px; height:96px; border-radius:50%; background-color:#ffffff; border:3px solid #926F74;">
                                        <span class="medallion-number" style="font-family: Georgia, 'Times New Roman', serif; font-size:40px; font-weight:700; color:#4A0F18; line-height:1;">{{ $daysLeft }}</span>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:20px 0 0; color:#ffffff; font-size:14px; font-weight:600; letter-spacing:1.5px; text-transform:uppercase;">
                                Jour{{ $plural }} restant{{ $plural }}
                            </p>

                        </td>
                    </tr>

                    <tr>
                        <td class="stack-padding" style="padding:40px; color:#2B2B2B; font-size:16px; line-height:1.6;">

                            <p style="margin:0 0 16px;">Bonjour,</p>

                            <p style="margin:0 0 16px;">
                                Vous aviez sauvegardé la bourse <strong style="font-family: Georgia, 'Times New Roman', serif; color:#4A0F18;">{{ $scholarship->title }}</strong> dans vos favoris.
                            </p>

                            <p style="margin:0 0 16px;">
                                La date limite de candidature est dans <strong style="font-family: Georgia, 'Times New Roman', serif; color:#4A0F18;">{{ $daysLeft }} jour{{ $plural }}</strong>.
                            </p>

                            <p style="margin:0 0 32px;">
                                Ne manquez pas cette opportunité !
                            </p>

                            <table role="presentation" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" bgcolor="#4A0F18" style="border-radius:8px;">
                                        <a href="{{ config('app.frontend_url', 'http://localhost:4200') }}/scholarships/{{ $scholarship->id }}" target="_blank" style="display:inline-block; padding:14px 36px; font-size:16px; font-weight:600; color:#ffffff; text-decoration:none; border-radius:8px;">
                                            Voir la bourse
                                        </a>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <tr>
                        <td class="stack-padding" style="padding:24px 40px 36px; border-top:1px solid #EFEFEF;">
                            <p style="margin:0 0 6px; color:#707070; font-size:12px;">Cet email a été envoyé automatiquement par Next.</p>
                            <p style="margin:0; color:#707070; font-size:12px;">&copy; {{ date('Y') }} Next. Tous droits réservés.</p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>