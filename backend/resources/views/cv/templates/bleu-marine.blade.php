<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>CV - {{ $data['personal_info']['first_name'] ?? '' }} {{ $data['personal_info']['last_name'] ?? '' }}</title>

    <style>
        :root {
            --navy: #1E3A5F;
            --navy-dark: #16283F;
            --text-dark: #1E2A38;
            --soft: #5B6B7C;
            --line: #C9D3DC;
            --white: #FFFFFF;
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
            background: #EAEDF0;
            font-family: Arial, Helvetica, sans-serif;
            display: flex;
            justify-content: center;
            padding: 40px;
            min-height: 100vh;
        }

        .cv {
            width: 210mm;
            height: 297mm; /* 🔥 On fixe la hauteur pour remplir toute la page */
            background: var(--white);
            box-shadow: 0 20px 60px rgba(20, 30, 45, .2);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        /* HEADER */
        .header {
            position: relative;
            display: grid;
            grid-template-columns: 1fr 300px;
            padding: 50px 40px 40px 45px;
            min-height: 250px;
            flex-shrink: 0;
        }

        .name {
            font-size: 34px;
            line-height: 1.15;
            color: var(--navy);
            font-weight: 500;
        }

        .name b {
            display: block;
            font-weight: 800;
        }

        .role {
            margin-top: 16px;
            font-size: 17px;
            color: var(--text-dark);
            font-weight: bold;
        }

        /* photo */
        .photo-shape {
            position: absolute;
            top: 0;
            right: 0;
            width: 300px;
            height: 230px;
            background: var(--navy);
            border-radius: 0 0 0 90px;
        }

        .photo-wrap {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            height: 100%;
        }

        .photo {
            width: 210px;
            height: 230px;
            border-radius: 105px 105px 20px 20px;
            overflow: hidden;
            background: #DDE3E9;
            border: 6px solid var(--white);
            box-shadow: 0 8px 20px rgba(0, 0, 0, .15);
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
        }

        /* LAYOUT */
        .layout {
            display: grid;
            grid-template-columns: 270px 1fr;
            flex: 1; /* 🔥 Prend tout l'espace restant */
        }

        /* SIDEBAR */
        .sidebar {
            background: var(--navy);
            color: var(--white);
            padding: 35px 30px 30px 45px;
        }

        .sidebar h2 {
            font-size: 19px;
            font-weight: 800;
            margin-bottom: 14px;
        }

        .sidebar .section {
            margin-bottom: 34px;
        }

        .about-text {
            font-size: 12px;
            line-height: 1.8;
            color: #C9D6E3;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 12.5px;
            margin-bottom: 12px;
        }

        .contact-icon {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .12);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            flex-shrink: 0;
        }

        .skills-list {
            list-style: none;
            font-size: 13px;
        }

        .skills-list li {
            padding-left: 16px;
            position: relative;
            margin-bottom: 9px;
        }

        .skills-list li::before {
            content: "•";
            position: absolute;
            left: 0;
            color: var(--white);
        }

        /* Langues - Version simple sans anneaux */
        .lang-item {
            display: flex;
            justify-content: space-between;
            font-size: 12.5px;
            margin-bottom: 10px;
            color: #C9D6E3;
        }

        .lang-item .lang-level {
            color: var(--white);
            font-weight: bold;
        }

        /* MAIN */
        main {
            padding: 40px 45px 30px 40px;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 14px;
            font-size: 20px;
            font-weight: 800;
            color: var(--navy);
            padding-bottom: 10px;
            border-bottom: 1px solid var(--line);
            margin-bottom: 20px;
        }

        .section-title::after {
            content: "";
            flex: 1;
        }

        .job {
            margin-bottom: 24px;
        }

        .job-label {
            font-size: 12px;
            font-weight: bold;
            color: var(--soft);
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .job-top {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
        }

        .job-top h3 {
            font-size: 15.5px;
            color: var(--text-dark);
        }

        .job-dates {
            font-size: 12.5px;
            color: var(--soft);
            font-weight: bold;
        }

        .job ul {
            margin-top: 8px;
            padding-left: 18px;
        }

        .job ul li {
            font-size: 12.5px;
            color: var(--soft);
            line-height: 1.6;
            margin-bottom: 4px;
        }

        .edu-item {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 6px;
        }

        .edu-item h3 {
            font-size: 15px;
            color: var(--text-dark);
        }

        .edu-dates {
            font-size: 12.5px;
            color: var(--soft);
            font-weight: bold;
        }

        .edu-degree {
            font-size: 12.5px;
            color: var(--soft);
            margin-bottom: 20px;
        }

        .ref-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        .ref-item h3 {
            font-size: 14px;
            color: var(--text-dark);
            margin-bottom: 3px;
        }

        .ref-item .ref-role {
            font-size: 12.5px;
            color: var(--soft);
            margin-bottom: 8px;
        }

        .ref-item .ref-line {
            font-size: 12px;
            color: var(--soft);
            margin-bottom: 3px;
        }

        .ref-item .ref-line b {
            color: var(--text-dark);
        }

        /* 🔥 FOOTER pour remplir la page */
        .cv-footer {
            background: var(--navy);
            color: rgba(255, 255, 255, 0.6);
            text-align: center;
            font-size: 10px;
            padding: 8px 0;
            flex-shrink: 0;
            margin-top: auto;
            font-family: Arial, Helvetica, sans-serif;
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

            <div class="photo-shape"></div>

            <div>
                <h1 class="name">
                    {{ $data['personal_info']['first_name'] ?? 'Prénom' }}<br><b>{{ $data['personal_info']['last_name'] ?? 'Nom' }}</b>
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

        <div class="layout">

            <aside class="sidebar">

                @if(!empty($data['summary']))
                <div class="section">
                    <h2>À propos</h2>
                    <p class="about-text">{{ $data['summary'] }}</p>
                </div>
                @endif

                <div class="section">
                    <h2>Contact</h2>

                    <div class="contact-item">
                        <span class="contact-icon">☎</span>
                        {{ $data['personal_info']['phone'] ?? '+229 00 00 00 00' }}
                    </div>

                    <div class="contact-item">
                        <span class="contact-icon">@</span>
                        {{ $data['personal_info']['email'] ?? 'nom@email.com' }}
                    </div>

                    <div class="contact-item">
                        <span class="contact-icon">⚲</span>
                        {{ $data['personal_info']['address'] ?? 'Cotonou, Bénin' }}
                    </div>
                </div>

                @if(!empty($data['skills']) && count($data['skills']) > 0)
                <div class="section">
                    <h2>Compétences</h2>

                    <ul class="skills-list">
                        @foreach($data['skills'] as $skill)
                        <li>
                            {{ is_array($skill) ? ($skill['name'] ?? '') : $skill }}
                            @if(is_array($skill) && isset($skill['level']))
                                — {{ $skill['level'] }}
                            @endif
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if(!empty($data['languages']) && count($data['languages']) > 0)
                <div class="section">
                    <h2>Langues</h2>

                    @foreach($data['languages'] as $lang)
                    <div class="lang-item">
                        <span>{{ is_array($lang) ? ($lang['language_name'] ?? '') : $lang }}</span>
                        <span class="lang-level">{{ is_array($lang) ? ($lang['language_level'] ?? '') : '' }}</span>
                    </div>
                    @endforeach
                </div>
                @endif

            </aside>

            <main>

                @if(!empty($data['experiences']) && count($data['experiences']) > 0)
                <section class="section">

                    <div class="section-title">Expériences</div>

                    @foreach($data['experiences'] as $exp)
                    <div class="job">

                        <div class="job-label">{{ $exp['position'] ?? 'Poste' }}</div>

                        <div class="job-top">
                            <h3>{{ $exp['company'] ?? 'Entreprise' }}</h3>
                            <span class="job-dates">{{ $exp['start_date'] ?? '' }} – {{ $exp['end_date'] ?? '' }}</span>
                        </div>

                        @if(!empty($exp['description']))
                        <ul>
                            @foreach(explode("\n", $exp['description']) as $line)
                                @if(trim($line))
                                    <li>{{ trim($line) }}</li>
                                @endif
                            @endforeach
                        </ul>
                        @endif

                    </div>
                    @endforeach

                </section>
                @endif

                @if(!empty($data['educations']) && count($data['educations']) > 0)
                <section class="section">

                    <div class="section-title">Formations</div>

                    @foreach($data['educations'] as $edu)
                    <div class="edu-item">
                        <h3>{{ $edu['school'] ?? 'Université' }}</h3>
                        <span class="edu-dates">{{ $edu['start_date'] ?? '' }} – {{ $edu['end_date'] ?? '' }}</span>
                    </div>

                    <div class="edu-degree">{{ $edu['degree'] ?? '' }}</div>
                    @endforeach

                </section>
                @endif

                @if(!empty($data['interests']) && count($data['interests']) > 0)
                <section class="section">

                    <div class="section-title">Projets & Intérêts</div>

                    <p style="font-size:12.5px;color:var(--soft);line-height:1.6;">
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