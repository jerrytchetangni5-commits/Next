@php
    $plural = $daysLeft > 1 ? 's' : '';
    $greeting = $userName ? "Bonjour {$userName}," : "Bonjour,";
    $urgency = $daysLeft <= 3 ? '   Dernières heures !' : ' Ne manquez pas cette opportunité !';
@endphp

<!DOCTYPE html>
<html lang="fr" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rappel de date limite</title>
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
<body>

    <!-- PREHEADER : invisible dans la boîte de réception, visible en aperçu -->
    <div style="display:none; max-height:0; overflow:hidden; mso-hide:all; font-size:1px; line-height:1px; color:#F5F1F0;">
        Plus que {{ $daysLeft }} jour{{ $plural }} pour candidater à {{ $scholarship->title }}.
        &nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;
    </div>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" bgcolor="#F5F1F0" style="background: linear-gradient(180deg, #F5F1F0 0%, #F2E9E7 100%);">
        <tr>
            <td align="center" style="padding:48px 16px;">

                <table role="presentation" width="600" cellpadding="0" cellspacing="0" bgcolor="#ffffff" style="width:100%; max-width:600px; background-color:#ffffff; border-radius:20px; overflow:hidden; box-shadow:0 16px 48px rgba(74,15,24,0.12); font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

                    <!-- HEADER BORDEAUX AVEC LOGO ET COMPTE À REBOURS -->
                    <tr>
                        <td align="center" class="header-padding" bgcolor="#4A0F18" style="background: linear-gradient(135deg, #4A0F18 0%, #6B1D2A 100%); padding:44px 40px 40px;">

                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/next.png'))) }}" alt="Next" style="display:block; max-width:140px; margin:0 auto 24px; height:auto;">

                            <p style="margin:0 0 20px; color:rgba(255,255,255,0.6); font-size:13px; font-weight:600; letter-spacing:2px; text-transform:uppercase;">
                                Rappel de date limite
                            </p>

                            <!-- MÉDAILLON DES JOURS -->
                            <table role="presentation" cellpadding="0" cellspacing="0" class="medallion" style="margin:0 auto;">
                                <tr>
                                    <td width="100" height="100" align="center" valign="middle" bgcolor="#ffffff" style="width:100px; height:100px; border-radius:50%; background-color:#ffffff; border:4px solid #B28A8E;">
                                        <span class="medallion-number" style="font-family: Georgia, 'Times New Roman', serif; font-size:44px; font-weight:700; color:#4A0F18; line-height:1;">{{ $daysLeft }}</span>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:16px 0 0; color:#ffffff; font-size:14px; font-weight:600; letter-spacing:2px; text-transform:uppercase; opacity:0.9;">
                                Jour{{ $plural }} restant{{ $plural }}
                            </p>

                            <p style="margin:4px 0 0; color:rgba(255,255,255,0.5); font-size:13px; font-weight:400;">
                                avant la clôture des candidatures
                            </p>

                        </td>
                    </tr>

                    <!-- CORPS : MESSAGE PERSONNALISÉ -->
                    <tr>
                        <td class="stack-padding" style="padding:40px 40px 32px; color:#2B2B2B; font-size:16px; line-height:1.7;">

                            <p style="margin:0 0 16px; font-size:18px; font-weight:400;">
                                {{ $greeting }}
                            </p>

                            <p style="margin:0 0 12px;">
                                Vous aviez sauvegardé la bourse <strong style="font-family: Georgia, 'Times New Roman', serif; color:#4A0F18;">{{ $scholarship->title }}</strong> dans vos favoris.
                            </p>

                            <p style="margin:0 0 12px;">
                                La date limite de candidature est dans <strong style="font-family: Georgia, 'Times New Roman', serif; color:#4A0F18;">{{ $daysLeft }} jour{{ $plural }}</strong>.
                            </p>

                            <p style="margin:0 0 28px; font-weight:500; color:#4A0F18;">
                                {{ $urgency }}
                            </p>

                            <!-- BOUTON ÉLÉGANT -->
                            <table role="presentation" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" bgcolor="#4A0F18" style="border-radius:10px; background: linear-gradient(135deg, #4A0F18 0%, #6B1D2A 100%);">
                                        <a href="{{ config('app.frontend_url', 'http://localhost:4200') }}/scholarships/{{ $scholarship->id }}" target="_blank" style="display:inline-block; padding:16px 44px; font-size:17px; font-weight:600; color:#ffffff; text-decoration:none; border-radius:10px; letter-spacing:0.3px;">
                                            Voir la bourse →
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- NOTE FINALE -->
                            <p style="margin:28px 0 0; color:#8A8A8A; font-size:14px; border-top:1px solid #EFEFEF; padding-top:28px;">
                                <strong>Conseil :</strong> Préparez vos documents dès maintenant pour ne pas manquer cette chance.
                            </p>

                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td class="stack-padding" style="padding:16px 40px 36px; border-top:1px solid #EFEFEF;">
                            <p style="margin:0 0 4px; color:#A0A0A0; font-size:13px;">
                                Cet email a été envoyé automatiquement par <strong style="color:#4A0F18;">Next</strong>.
                            </p>
                            <p style="margin:0; color:#A0A0A0; font-size:13px;">
                                &copy; {{ date('Y') }} Next. Tous droits réservés.
                            </p>
                            <p style="margin:12px 0 0; color:#C0C0C0; font-size:11px;">
                                Vous recevez cet email car vous avez sauvegardé cette bourse dans vos favoris.
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>