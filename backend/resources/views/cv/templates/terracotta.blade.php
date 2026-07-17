<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>CV Terracotta - {{ $data['personal_info']['first_name'] ?? '' }} {{ $data['personal_info']['last_name'] ?? '' }}</title>

    <style>
        :root {
            --background: #FAF1E6;
            --paper: #FAF1E6;
            --primary: #5C3D2E;
            --secondary: #8C6350;
            --accent: #B98268;
            --accent-dark: #7A4B36;
            --pill: #EBD3BE;
            --text: #3E2C22;
            --soft: #8C6350;
            --line: #D8B79A;
            --progress: #5C3D2E;
            --progress-bg: #E7CBAF;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: #E9E1D8;
            font-family: "Georgia", "Times New Roman", serif;
            display: flex;
            justify-content: center;
            padding: 40px;
        }

        .cv {
            width: 210mm;
            min-height: 297mm;
            background: var(--paper);
            box-shadow: 0 20px 60px rgba(60, 40, 25, .18);
            padding: 0;
            position: relative;
            overflow: hidden;
        }

        /* décoration header */
        .deco-block {
            position: absolute;
            top: 0;
            right: 0;
            width: 230px;
            height: 100%;
            background: var(--accent);
            opacity: .9;
            z-index: 0;
        }

        .deco-arc {
            position: absolute;
            top: -60px;
            left: -90px;
            width: 320px;
            height: 320px;
            border-radius: 50%;
            background: var(--pill);
            opacity: .6;
            z-index: 0;
        }

        /* HEADER */
        .header {
            position: relative;
            display: grid;
            grid-template-columns: 1fr 230px;
            padding: 55px 55px 40px 55px;
            z-index: 1;
        }

        .header-left {
            padding-right: 30px;
        }

        .name {
            font-size: 46px;
            letter-spacing: 6px;
            line-height: 1.1;
            color: var(--primary);
            text-transform: uppercase;
            font-weight: 700;
        }

        .role {
            margin-top: 16px;
            color: var(--primary);
            letter-spacing: 1px;
            font-size: 16px;
            font-weight: bold;
            font-family: Arial, sans-serif;
        }

        /* photo */
        .photo-wrap {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .photo {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            overflow: hidden;
            background: #F1E4D6;
            border: 6px solid var(--paper);
            box-shadow: 0 8px 24px rgba(0, 0, 0, .18);
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
            color: var(--soft);
            font-size: 13px;
            font-family: Arial, sans-serif;
        }

        /* CONTENU */
        .content {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: 230px 1fr;
            gap: 45px;
            padding: 0 55px 55px 55px;
            font-family: Arial, sans-serif;
        }

        /* SIDEBAR GAUCHE */
        .sidebar {
            padding-right: 10px;
        }

        .contact {
            color: var(--text);
            font-size: 13px;
            line-height: 2.1;
        }

        .contact-icon {
            display: inline-block;
            width: 16px;
            color: var(--accent-dark);
            font-weight: bold;
            margin-right: 6px;
        }

        /* pill title */
        .pill-title {
            display: inline-block;
            background: var(--pill);
            color: var(--primary);
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 8px 18px;
            border-radius: 20px;
            margin-bottom: 18px;
        }

        .section {
            margin-bottom: 34px;
        }

        /* education dans sidebar */
        .edu-item {
            margin-bottom: 18px;
        }

        .edu-item .year {
            color: var(--accent-dark);
            font-size: 12px;
            font-weight: bold;
        }

        .edu-item h4 {
            font-size: 13px;
            color: var(--text);
            margin: 3px 0;
        }

        .edu-item p {
            color: var(--soft);
            font-size: 12px;
            line-height: 1.5;
        }

        /* skills - SANS BARRES, juste une liste */
        .skill-item {
            font-size: 13px;
            color: var(--text);
            margin-bottom: 8px;
            padding-left: 16px;
            position: relative;
        }

        .skill-item::before {
            content: "▸";
            color: var(--accent-dark);
            position: absolute;
            left: 0;
        }

        /* langues dans sidebar */
        .language {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            margin-bottom: 10px;
            color: var(--text);
        }

        .level {
            color: var(--accent-dark);
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px;
        }

        /* MAIN DROITE */
        main {
            padding-left: 10px;
            border-left: 1px solid var(--line);
        }

        .summary {
            color: var(--soft);
            font-size: 13px;
            line-height: 1.8;
            margin-bottom: 38px;
            padding-left: 22px;
        }

        /* timeline */
        .item {
            position: relative;
            margin-bottom: 26px;
            padding-left: 22px;
        }

        .item::before {
            content: "";
            position: absolute;
            left: -5px;
            top: 5px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--accent-dark);
        }

        .item::after {
            content: "";
            position: absolute;
            left: 0;
            top: 14px;
            bottom: -26px;
            width: 1px;
            background: var(--line);
        }

        .item:last-child::after {
            display: none;
        }

        .item .meta {
            color: var(--accent-dark);
            font-size: 12px;
            font-weight: bold;
        }

        .item h3 {
            font-size: 15px;
            color: var(--text);
            margin: 4px 0 6px 0;
        }

        .item .description {
            color: var(--soft);
            font-size: 13px;
            line-height: 1.6;
        }

        .projects-text {
            padding-left: 22px;
            color: var(--soft);
            font-size: 13px;
            line-height: 1.6;
        }

        @media print {
            body {
                padding: 0;
                background: white;
            }
            .cv {
                box-shadow: none;
            }
        }
    </style>

</head>

<body>

    <div class="cv">

        <div class="deco-arc"></div>
        <div class="deco-block"></div>

        <header class="header">

            <div class="header-left">

                <h1 class="name">
                    {{ $data['personal_info']['first_name'] ?? 'Prénom' }} {{ $data['personal_info']['last_name'] ?? 'Nom' }}
                </h1>

                <div class="role">
                    {{ $data['personal_info']['title'] ?? 'Développeur Full Stack' }}
                </div>

            </div>

            <div class="photo-wrap">

                <div class="photo">
                    @if(!empty($data['personal_info']['photo']))
                        <img src="{{ $data['personal_info']['photo'] }}" alt="Photo">
                    @else
                        <div class="photo-placeholder">Photo</div>
                    @endif
                </div>

            </div>

        </header>

        <div class="content">

            <aside class="sidebar">

                <section class="section">
                    <div class="pill-title">Contact</div>
                    <div class="contact">
                        <span class="contact-icon">@</span> {{ $data['personal_info']['email'] ?? 'nom@email.com' }}<br>
                        <span class="contact-icon">☎</span> {{ $data['personal_info']['phone'] ?? '+229 00 00 00 00' }}<br>
                        <span class="contact-icon">⚲</span> {{ $data['personal_info']['address'] ?? 'Cotonou, Bénin' }}
                    </div>
                </section>

                @if(!empty($data['educations']) && count($data['educations']) > 0)
                <section class="section">
                    <div class="pill-title">Formations</div>
                    @foreach($data['educations'] as $edu)
                    <div class="edu-item">
                        <div class="year">{{ $edu['start_date'] ?? '' }} - {{ $edu['end_date'] ?? '' }}</div>
                        <h4>{{ $edu['degree'] ?? '' }}</h4>
                        <p>{{ $edu['school'] ?? '' }} @if(!empty($edu['description'])) — {{ $edu['description'] }} @endif</p>
                    </div>
                    @endforeach
                </section>
                @endif

                @if(!empty($data['skills']) && count($data['skills']) > 0)
                <section class="section">
                    <div class="pill-title">Compétences</div>
                    @foreach($data['skills'] as $skill)
                    <div class="skill-item">
                        {{ is_array($skill) ? ($skill['name'] ?? '') : $skill }}
                    </div>
                    @endforeach
                </section>
                @endif

                @if(!empty($data['languages']) && count($data['languages']) > 0)
                <section class="section">
                    <div class="pill-title">Langues</div>
                    @foreach($data['languages'] as $lang)
                    <div class="language">
                        <span>{{ is_array($lang) ? ($lang['language_name'] ?? '') : $lang }}</span>
                        <span class="level">{{ is_array($lang) ? ($lang['language_level'] ?? '') : '' }}</span>
                    </div>
                    @endforeach
                </section>
                @endif

            </aside>

            <main>

                @if(!empty($data['summary']))
                <p class="summary">
                    {{ $data['summary'] }}
                </p>
                @endif

                @if(!empty($data['experiences']) && count($data['experiences']) > 0)
                <section class="section">
                    <div class="pill-title">Expériences</div>
                    @foreach($data['experiences'] as $exp)
                    <div class="item">
                        <div class="meta">
                            {{ $exp['start_date'] ?? '' }} - {{ $exp['end_date'] ?? '' }} @if(!empty($exp['company'])) | {{ $exp['company'] }} @endif
                        </div>
                        <h3>{{ $exp['position'] ?? '' }}</h3>
                        <p class="description">{{ $exp['description'] ?? '' }}</p>
                    </div>
                    @endforeach
                </section>
                @endif

                @if(!empty($data['interests']) && count($data['interests']) > 0)
                <section class="section">
                    <div class="pill-title">Projets & Intérêts</div>
                    <p class="projects-text">
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