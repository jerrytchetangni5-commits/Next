<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV - {{ $data['personal_info']['first_name'] ?? '' }} {{ $data['personal_info']['last_name'] ?? '' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --dark: #0F3D30;
            --dark-deep: #0A2E23;
            --accent: #22795C;
            --accent-light: #3EA07C;
            --white: #FFFFFF;
            --offwhite: #FAFAF8;
            --text-dark: #1E1E1E;
            --text-gray: #48504C;
            --text-light: #F2F5F3;
            --muted: #B9CFC5;
            --track: #173226;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html, body {
            height: 100%;
        }

        body {
            background: #EDEDED;
            font-family: 'Poppins', 'Segoe UI', Arial, Helvetica, sans-serif;
            display: flex;
            justify-content: center;
            padding: 40px;
            min-height: 100vh;
        }

        .cv {
            width: 210mm;
            height: 297mm; /* 🔥 On fixe la hauteur pour remplir toute la page */
            background: var(--offwhite);
            box-shadow: 0 20px 60px rgba(0, 0, 0, .2);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        /* HEADER */
        .header {
            display: grid;
            grid-template-columns: 270px 1fr;
            background: var(--dark);
            flex-shrink: 0;
        }

        .header-photo {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 44px 30px;
        }

        .photo-ring {
            position: absolute;
            width: 212px;
            height: 212px;
            border: 16px solid var(--accent-light);
            border-radius: 50%;
            top: 24px;
            left: 4px;
            opacity: .9;
        }

        .photo-circle {
            position: relative;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: #D8D8D6;
            border: 6px solid var(--white);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1;
        }

        .photo-circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-circle span {
            font-size: 13px;
            color: #8A8A8A;
            text-align: center;
        }

        .header-text {
            padding: 56px 50px 44px 16px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .name {
            font-size: 44px;
            font-weight: 800;
            line-height: 1.08;
            color: var(--white);
        }

        .role {
            font-size: 21px;
            font-weight: 500;
            color: var(--text-light);
            margin-top: 8px;
        }

        .divider {
            border: none;
            height: 1px;
            background: rgba(255, 255, 255, .35);
            margin: 20px 0 18px 0;
            width: 92%;
        }

        .intro {
            font-size: 13px;
            line-height: 1.75;
            color: var(--text-light);
            opacity: .9;
            max-width: 92%;
            text-align: justify;
        }

        /* CONTENT */
        .content {
            display: grid;
            grid-template-columns: 270px 1fr;
            flex: 1; /* 🔥 Prend tout l'espace restant */
        }

        .sidebar {
            background: var(--dark);
            color: var(--white);
            padding: 38px 32px 30px 32px;
        }

        .main {
            background: var(--offwhite);
            padding: 42px 42px 30px 40px;
        }

        .pill {
            display: inline-block;
            background: var(--accent);
            color: var(--white);
            font-size: 17px;
            font-weight: 700;
            padding: 10px 26px 10px 32px;
            margin: 0 0 20px -32px;
            border-radius: 0 24px 24px 0;
        }

        .pill:not(:first-child) {
            margin-top: 28px;
        }

        .pill-main {
            display: inline-block;
            background: var(--dark);
            color: var(--white);
            font-size: 19px;
            font-weight: 700;
            padding: 12px 30px 12px 40px;
            margin: 0 0 24px -40px;
            border-radius: 0 26px 26px 0;
        }

        .pill-main:not(:first-child) {
            margin-top: 36px;
        }

        /* contact */
        .contact-item {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 12.5px;
            margin-bottom: 16px;
            color: var(--text-light);
        }

        .icon-circle {
            width: 26px;
            height: 26px;
            min-width: 26px;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, .7);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-circle svg {
            width: 13px;
            height: 13px;
            stroke: var(--white);
        }

        /* education */
        .edu-year {
            font-size: 13px;
            font-weight: 700;
            color: var(--white);
        }

        .edu-univ {
            font-size: 12.5px;
            font-style: italic;
            color: var(--muted);
            margin-top: 4px;
        }

        .edu-degree {
            font-size: 13px;
            font-weight: 700;
            color: var(--white);
            margin-top: 10px;
        }

        .edu-desc {
            font-size: 11.5px;
            line-height: 1.6;
            color: var(--muted);
            margin-top: 6px;
        }

        /* languages */
        .lang-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            font-size: 12.5px;
            color: var(--text-light);
        }

        .lang-level {
            font-size: 10.5px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--muted);
        }

        /* experience */
        .exp-item {
            margin-bottom: 26px;
        }

        .exp-header {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-dark);
        }

        .exp-date {
            font-weight: 500;
            color: var(--text-gray);
        }

        .exp-role {
            font-size: 13px;
            font-style: italic;
            color: var(--text-gray);
            margin: 4px 0 8px 0;
        }

        .exp-bullets {
            padding-left: 18px;
        }

        .exp-bullets li {
            font-size: 12.5px;
            line-height: 1.65;
            color: #3C3C3C;
            margin-bottom: 4px;
        }

        .exp-bullets li::marker {
            color: var(--accent);
        }

        .skill-item {
            font-size: 13px;
            color: var(--text-dark);
            margin-bottom: 8px;
            padding-left: 16px;
            position: relative;
        }

        .skill-item::before {
            content: "•";
            position: absolute;
            left: 0;
            color: var(--accent);
        }

        .cv-footer {
            background: var(--dark);
            color: var(--text-light);
            text-align: center;
            font-size: 10px;
            padding: 8px 0;
            flex-shrink: 0;
            opacity: 0.7;
            margin-top: auto;
        }

        @media print {
            body {
                padding: 0;
                background: white;
            }
            .cv {
                box-shadow: none;
                height: 100vh;
            }
        }
    </style>
</head>
<body>

<div class="cv">

    <header class="header">
        <div class="header-photo">
            <div class="photo-ring"></div>
            <div class="photo-circle">
                @if(!empty($data['personal_info']['photo']))
                    <img src="{{ $data['personal_info']['photo'] }}" alt="Photo">
                @else
                    <span>Photo</span>
                @endif
            </div>
        </div>
        <div class="header-text">
            <h1 class="name">{{ $data['personal_info']['first_name'] ?? 'Prénom' }} {{ $data['personal_info']['last_name'] ?? 'Nom' }}</h1>
            <div class="role">{{ $data['personal_info']['title'] ?? 'Développeur Full Stack' }}</div>
            <hr class="divider">
            <p class="intro">{{ $data['summary'] ?? 'Profil professionnel de la personne. Présentation courte des compétences, objectifs et expériences.' }}</p>
        </div>
    </header>

    <div class="content">

        <aside class="sidebar">

            <div class="pill">Contact</div>

            <div class="contact-item">
                <span class="icon-circle">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                </span>
                {{ $data['personal_info']['phone'] ?? '+229 00 00 00 00' }}
            </div>

            <div class="contact-item">
                <span class="icon-circle">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                </span>
                {{ $data['personal_info']['email'] ?? 'nom@email.com' }}
            </div>

            <div class="contact-item">
                <span class="icon-circle">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                </span>
                {{ $data['personal_info']['address'] ?? 'Cotonou, Bénin' }}
            </div>

            @if(!empty($data['educations']) && count($data['educations']) > 0)
            <div class="pill">Formation</div>
            @foreach($data['educations'] as $edu)
            <div class="edu-year">{{ $edu['start_date'] ?? '' }} - {{ $edu['end_date'] ?? '' }}</div>
            <div class="edu-univ">{{ $edu['school'] ?? 'Université' }}</div>
            <div class="edu-degree">{{ $edu['degree'] ?? '' }}</div>
            <div class="edu-desc">{{ $edu['description'] ?? '' }}</div>
            @endforeach
            @endif

            @if(!empty($data['languages']) && count($data['languages']) > 0)
            <div class="pill">Langues</div>
            @foreach($data['languages'] as $lang)
            <div class="lang-item">
                <span>{{ is_array($lang) ? ($lang['language_name'] ?? '') : $lang }}</span>
                <span class="lang-level">{{ is_array($lang) ? ($lang['language_level'] ?? '') : '' }}</span>
            </div>
            @endforeach
            @endif

        </aside>

        <main class="main">

            @if(!empty($data['experiences']) && count($data['experiences']) > 0)
            <div class="pill-main">Expérience Professionnelle</div>

            @foreach($data['experiences'] as $exp)
            <div class="exp-item">
                <div class="exp-header">
                    {{ $exp['company'] ?? 'Entreprise' }}
                    <span class="exp-date">| {{ $exp['start_date'] ?? '' }} - {{ $exp['end_date'] ?? '' }}</span>
                </div>
                <div class="exp-role">{{ $exp['position'] ?? '' }}</div>
                @if(!empty($exp['description']))
                <ul class="exp-bullets">
                    @foreach(explode("\n", $exp['description']) as $line)
                        @if(trim($line))
                            <li>{{ trim($line) }}</li>
                        @endif
                    @endforeach
                </ul>
                @endif
            </div>
            @endforeach
            @endif

            @if(!empty($data['skills']) && count($data['skills']) > 0)
            <div class="pill-main">Compétences</div>

            @foreach($data['skills'] as $skill)
            <div class="skill-item">
                {{ is_array($skill) ? ($skill['name'] ?? '') : $skill }}
                @if(is_array($skill) && isset($skill['level']))
                    — {{ $skill['level'] }}
                @endif
            </div>
            @endforeach
            @endif

        </main>

    </div>
</div>

</body>
</html>