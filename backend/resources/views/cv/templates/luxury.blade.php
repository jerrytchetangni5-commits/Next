<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>CV Luxury - {{ $data['personal_info']['first_name'] ?? '' }} {{ $data['personal_info']['last_name'] ?? '' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,500;1,600&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --page-bg: #EFEAE2;
            --card: #2A241E;
            --accent: #C9A876;
            --accent-strong: #DCC298;
            --heading: #F6F1E8;
            --body: #B7AE9E;
            --muted: #8B8375;
            --border: rgba(201, 168, 118, .32);
            --track: rgba(255, 255, 255, .08);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: var(--page-bg);
            font-family: "Poppins", Arial, sans-serif;
            display: flex;
            justify-content: center;
            padding: 40px;
        }

        .cv {
            width: 210mm;
            min-height: 297mm;
            background: var(--card);
            box-shadow: 0 20px 60px rgba(20, 16, 10, .35);
            padding: 55px 60px;
            position: relative;
            overflow: hidden;
        }

        /* décoration géométrique (coins) */
        .deco { position: absolute; width: 320px; height: 320px; z-index: 0; }
        .deco.tl { top: -70px; left: -70px; }
        .deco.br { bottom: -70px; right: -70px; transform: rotate(180deg); }
        .deco span { position: absolute; border-radius: 50%; box-sizing: border-box; }
        .deco .c1 { width: 160px; height: 160px; top: 0; left: 0; background: var(--accent); opacity: .14; }
        .deco .c2 { width: 160px; height: 160px; top: 0; left: 150px; border: 22px solid var(--accent); opacity: .5; }
        .deco .c3 { width: 160px; height: 160px; top: 150px; left: 0; border: 22px solid var(--accent); opacity: .32; }
        .deco .c4 { width: 160px; height: 160px; top: 150px; left: 150px; background: var(--accent); opacity: .2; }

        /* HEADER */
        .header {
            display: grid;
            grid-template-columns: 150px 1fr;
            gap: 38px;
            position: relative;
            z-index: 1;
        }

        .photo {
            width: 150px;
            height: 170px;
            border-radius: 10px;
            overflow: hidden;
            background: rgba(255, 255, 255, .05);
            border: 1px solid var(--border);
            position: relative;
            z-index: 2;
        }

        .photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-placeholder {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--muted);
            font-size: 12px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .name {
            font-family: "Playfair Display", serif;
            font-size: 44px;
            letter-spacing: 2px;
            color: var(--heading);
            text-transform: uppercase;
            font-weight: 700;
            line-height: 1.12;
            padding-top: 8px;
        }

        .role {
            margin-top: 16px;
            font-family: "Playfair Display", serif;
            font-style: italic;
            color: var(--accent-strong);
            font-size: 18px;
            font-weight: 500;
        }

        /* CONTENU */
        .content {
            display: grid;
            grid-template-columns: 260px 1fr;
            gap: 50px;
            margin-top: 45px;
            position: relative;
            z-index: 1;
        }

        .section { margin-bottom: 32px; }
        .section:last-child { margin-bottom: 0; }

        .section-title {
            font-family: "Playfair Display", serif;
            color: var(--heading);
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 16px;
            line-height: 1.25;
        }

        .profile-text {
            color: var(--body);
            font-size: 13px;
            line-height: 1.85;
        }

        /* LANGUES PARLEES */
        .lang-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            color: var(--body);
            margin-bottom: 10px;
        }
        .lang-row .level {
            color: var(--accent);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 10.5px;
            letter-spacing: .5px;
        }

        /* BOITE (competences + coordonnees) */
        .boxed {
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 26px 24px;
        }
        .boxed .section { margin-bottom: 28px; }
        .boxed .section:last-child { margin-bottom: 0; }

        /* COMPETENCES - sans barres, liste simple */
        .skill-item {
            font-size: 13px;
            color: var(--body);
            margin-bottom: 10px;
            padding-left: 18px;
            position: relative;
        }
        .skill-item::before {
            content: "◆";
            position: absolute;
            left: 0;
            color: var(--accent);
            font-size: 10px;
            top: 2px;
        }

        /* COORDONNEES */
        .contact-row {
            display: flex;
            align-items: center;
            gap: 13px;
            margin-bottom: 15px;
            font-size: 12px;
            color: var(--body);
            word-break: break-word;
        }
        .contact-row:last-child { margin-bottom: 0; }
        .icon-circle {
            width: 30px;
            height: 30px;
            min-width: 30px;
            border: 1px solid var(--accent);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .icon-circle svg {
            width: 14px;
            height: 14px;
            fill: none;
            stroke: var(--accent);
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        /* EXPERIENCE / FORMATION */
        .item { margin-bottom: 24px; }
        .item:last-child { margin-bottom: 0; }
        .item h3 {
            font-size: 14.5px;
            color: var(--accent);
            font-weight: 700;
        }
        .item .subtitle {
            font-style: italic;
            color: var(--heading);
            font-size: 13.5px;
            margin-top: 3px;
            font-weight: 500;
        }
        .item .meta {
            color: var(--accent);
            opacity: .75;
            font-size: 12px;
            margin: 5px 0 10px;
        }
        .item ul { list-style: none; }
        .item ul li {
            color: var(--body);
            font-size: 12.5px;
            line-height: 1.6;
            padding-left: 16px;
            position: relative;
            margin-bottom: 5px;
        }
        .item ul li::before {
            content: "";
            position: absolute;
            left: 0;
            top: 7px;
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: var(--accent);
        }

        @media print {
            body { padding: 0; background: white; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .cv { box-shadow: none; }
        }
    </style>
</head>
<body>

    <div class="cv">

        <!-- Décoration -->
        <div class="deco tl"><span class="c1"></span><span class="c2"></span><span class="c3"></span><span class="c4"></span></div>
        <div class="deco br"><span class="c1"></span><span class="c2"></span><span class="c3"></span><span class="c4"></span></div>

        <!-- HEADER -->
        <header class="header">

            <div class="photo">
                @if(!empty($data['personal_info']['photo']))
                    <img src="{{ $data['personal_info']['photo'] }}" alt="Photo">
                @else
                    <div class="photo-placeholder">Photo</div>
                @endif
            </div>

            <div>
                <h1 class="name">{{ $data['personal_info']['first_name'] ?? 'Prénom' }}<br>{{ $data['personal_info']['last_name'] ?? 'Nom' }}</h1>
                <div class="role">{{ $data['personal_info']['title'] ?? 'Développeur Full Stack' }}</div>
            </div>

        </header>

        <!-- CONTENU -->
        <div class="content">

            <!-- SIDEBAR -->
            <aside>

                @if(!empty($data['summary']))
                <section class="section">
                    <div class="section-title">Profil</div>
                    <p class="profile-text">{{ $data['summary'] }}</p>
                </section>
                @endif

                @if(!empty($data['languages']) && count($data['languages']) > 0)
                <section class="section">
                    <div class="section-title">Langues Parlées</div>
                    @foreach($data['languages'] as $lang)
                    <div class="lang-row">
                        <span>{{ is_array($lang) ? ($lang['language_name'] ?? '') : $lang }}</span>
                        <span class="level">{{ is_array($lang) ? ($lang['language_level'] ?? '') : '' }}</span>
                    </div>
                    @endforeach
                </section>
                @endif

                <div class="boxed">

                    @if(!empty($data['skills']) && count($data['skills']) > 0)
                    <section class="section">
                        <div class="section-title">Compétences</div>

                        @foreach($data['skills'] as $skill)
                        <div class="skill-item">
                            {{ is_array($skill) ? ($skill['name'] ?? '') : $skill }}
                            @if(is_array($skill) && isset($skill['level']))
                                — {{ $skill['level'] }}
                            @endif
                        </div>
                        @endforeach

                    </section>
                    @endif

                    <section class="section">
                        <div class="section-title">Coordonnées</div>

                        <div class="contact-row">
                            <div class="icon-circle"><svg viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></div>
                            <span>{{ $data['personal_info']['email'] ?? 'nom@email.com' }}</span>
                        </div>

                        <div class="contact-row">
                            <div class="icon-circle"><svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.12.9.35 1.78.68 2.61a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.34a2 2 0 0 1 2.11-.45c.83.33 1.71.56 2.61.68A2 2 0 0 1 22 16.92z"/></svg></div>
                            <span>{{ $data['personal_info']['phone'] ?? '+229 00 00 00 00' }}</span>
                        </div>

                        <div class="contact-row">
                            <div class="icon-circle"><svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></div>
                            <span>{{ $data['personal_info']['address'] ?? 'Cotonou, Bénin' }}</span>
                        </div>

                    </section>

                </div>

            </aside>

            <!-- MAIN -->
            <main>

                @if(!empty($data['educations']) && count($data['educations']) > 0)
                <section class="section">
                    <div class="section-title">Parcours Universitaire</div>

                    @foreach($data['educations'] as $edu)
                    <div class="item">
                        <h3>{{ $edu['degree'] ?? '' }}</h3>
                        <div class="subtitle">{{ $edu['school'] ?? '' }}</div>
                        <div class="meta">{{ $edu['start_date'] ?? '' }} - {{ $edu['end_date'] ?? '' }}</div>
                        @if(!empty($edu['description']))
                            <ul>
                                @foreach(explode("\n", $edu['description']) as $line)
                                    <li>{{ $line }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    @endforeach

                </section>
                @endif

                @if(!empty($data['experiences']) && count($data['experiences']) > 0)
                <section class="section">
                    <div class="section-title">Vie Professionnelle</div>

                    @foreach($data['experiences'] as $exp)
                    <div class="item">
                        <h3>{{ $exp['position'] ?? '' }}</h3>
                        <div class="subtitle">{{ $exp['company'] ?? '' }}</div>
                        <div class="meta">{{ $exp['start_date'] ?? '' }} - {{ $exp['end_date'] ?? '' }}</div>
                        @if(!empty($exp['description']))
                            <ul>
                                @foreach(explode("\n", $exp['description']) as $line)
                                    <li>{{ $line }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    @endforeach

                </section>
                @endif

                @if(!empty($data['interests']) && count($data['interests']) > 0)
                <section class="section">
                    <div class="section-title">Projets & Intérêts</div>
                    <p class="profile-text">
                        @foreach($data['interests'] as $interest)
                            {{ is_array($interest) ? ($interest['name'] ?? '') : $interest }}
                            @if(!$loop->last), @endif
                        @endforeach
                    </p>
                </section>
                @endif

            </main>

        </div>

    </div>

</body>
</html>