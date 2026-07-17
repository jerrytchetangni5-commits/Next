<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>CV Minimal - {{ $data['personal_info']['first_name'] ?? '' }} {{ $data['personal_info']['last_name'] ?? '' }}</title>

    <style>
        :root {
            --light: #DCDCDC;
            --light-panel: #E7E7E7;
            --dark: #262626;
            --dark-panel: #2B2B2B;
            --text-dark: #1E1E1E;
            --text-light: #F2F2F2;
            --muted: #B8B8B8;
            --line: #4A4A4A;
            --accent: #5C5C5C;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: #EDEDED;
            font-family: Arial, Helvetica, sans-serif;
            display: flex;
            justify-content: center;
            padding: 40px;
        }

        .cv {
            width: 210mm;
            min-height: 297mm;
            background: var(--light);
            box-shadow: 0 20px 60px rgba(0, 0, 0, .2);
            position: relative;
            overflow: hidden;
        }

        /* HEADER */
        .header {
            display: grid;
            grid-template-columns: 1fr 300px;
        }

        .header-left {
            background: var(--light);
            padding: 55px 40px 40px 45px;
        }

        .name {
            font-size: 44px;
            line-height: 1.05;
            font-weight: 800;
            color: var(--text-dark);
            text-transform: uppercase;
        }

        .role {
            margin-top: 22px;
            font-size: 14px;
            letter-spacing: 4px;
            font-weight: bold;
            color: var(--text-dark);
            text-transform: uppercase;
        }

        .header-right {
            background: var(--dark);
            display: flex;
            align-items: flex-end;
            justify-content: center;
            padding: 30px;
        }

        .photo {
            width: 100%;
            height: 220px;
            background: #F1F1F1;
            overflow: hidden;
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
            color: var(--accent);
            font-size: 13px;
        }

        /* CONTENT */
        .content {
            display: grid;
            grid-template-columns: 1fr 300px;
            min-height: calc(297mm - 265px);
        }

        .left {
            background: var(--light-panel);
            padding: 40px 40px 50px 45px;
        }

        .right {
            background: var(--dark-panel);
            color: var(--text-light);
            padding: 40px 35px 50px 35px;
        }

        /* section banner (flèche) */
        .banner {
            position: relative;
            background: #BFBFBF;
            color: var(--text-dark);
            font-size: 20px;
            font-weight: 800;
            padding: 10px 20px;
            margin-bottom: 22px;
            clip-path: polygon(0 0, 90% 0, 100% 50%, 90% 100%, 0 100%);
        }

        /* timeline */
        .timeline {
            position: relative;
            padding-left: 26px;
        }

        .timeline::before {
            content: "";
            position: absolute;
            left: 6px;
            top: 8px;
            bottom: 8px;
            width: 2px;
            background: var(--accent);
        }

        .titem {
            position: relative;
            margin-bottom: 26px;
        }

        .titem::before {
            content: "";
            position: absolute;
            left: -26px;
            top: 2px;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: var(--accent);
        }

        .titem .meta {
            font-size: 13px;
            font-weight: bold;
            color: var(--text-dark);
        }

        .titem .poste {
            font-size: 13px;
            font-style: italic;
            color: var(--text-dark);
            margin-bottom: 6px;
        }

        .titem .description {
            font-size: 12.5px;
            line-height: 1.6;
            color: #3C3C3C;
        }

        /* education */
        .edu-section {
            margin-top: 10px;
        }

        .edu-item {
            display: grid;
            grid-template-columns: 80px 1fr;
            gap: 16px;
            margin-bottom: 20px;
        }

        .edu-year {
            font-size: 13px;
            font-weight: bold;
            color: var(--text-dark);
        }

        .edu-year .univ {
            display: block;
            font-style: italic;
            font-weight: normal;
            margin-top: 4px;
            font-size: 12px;
        }

        .edu-desc {
            font-size: 12.5px;
            color: #3C3C3C;
            line-height: 1.6;
        }

        .edu-desc .degree {
            display: block;
            margin-top: 4px;
            font-weight: bold;
            color: var(--text-dark);
        }

        /* RIGHT COLUMN */
        .right h2 {
            font-size: 20px;
            font-weight: 800;
            margin-bottom: 14px;
            margin-top: 34px;
        }

        .right h2:first-child {
            margin-top: 0;
        }

        .about-text {
            font-size: 12.5px;
            line-height: 1.7;
            color: var(--muted);
        }

        .skills-list {
            list-style: none;
            font-size: 13px;
        }

        .skills-list li {
            padding-left: 16px;
            position: relative;
            margin-bottom: 8px;
        }

        .skills-list li::before {
            content: "•";
            position: absolute;
            left: 0;
            color: var(--text-light);
        }

        /* langues */
        .language {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            font-size: 13px;
        }

        .language-level {
            color: var(--muted);
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11.5px;
            letter-spacing: 1px;
        }

        /* contact */
        .contact-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            margin-bottom: 12px;
        }

        .contact-icon {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            border: 1px solid var(--text-light);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            flex-shrink: 0;
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

        <header class="header">

            <div class="header-left">

                <h1 class="name">
                    {{ $data['personal_info']['first_name'] ?? 'Prénom' }}<br>{{ $data['personal_info']['last_name'] ?? 'Nom' }}
                </h1>

                <div class="role">
                    {{ $data['personal_info']['title'] ?? 'Développeur Full Stack' }}
                </div>

            </div>

            <div class="header-right">

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

            <div class="left">

                @if(!empty($data['experiences']) && count($data['experiences']) > 0)
                <div class="banner">Expériences</div>

                <div class="timeline">
                    @foreach($data['experiences'] as $exp)
                    <div class="titem">
                        <div class="meta">
                            {{ $exp['company'] ?? '' }} @if(!empty($exp['start_date']) || !empty($exp['end_date'])) | {{ $exp['start_date'] ?? '' }} - {{ $exp['end_date'] ?? '' }} @endif
                        </div>
                        <div class="poste">
                            {{ $exp['position'] ?? '' }}
                        </div>
                        @if(!empty($exp['description']))
                        <p class="description">{{ $exp['description'] }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif

                @if(!empty($data['educations']) && count($data['educations']) > 0)
                <div class="edu-section">
                    <div class="banner">Formations</div>

                    @foreach($data['educations'] as $edu)
                    <div class="edu-item">
                        <div class="edu-year">
                            {{ $edu['start_date'] ?? '' }}<br>-{{ $edu['end_date'] ?? '' }}
                            <span class="univ">{{ $edu['school'] ?? '' }}</span>
                        </div>
                        <div class="edu-desc">
                            {{ $edu['description'] ?? '' }}
                            <span class="degree">{{ $edu['degree'] ?? '' }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

            </div>

            <div class="right">

                @if(!empty($data['summary']))
                <h2>À propos</h2>
                <p class="about-text">{{ $data['summary'] }}</p>
                @endif

                @if(!empty($data['skills']) && count($data['skills']) > 0)
                <h2>Compétences</h2>
                <ul class="skills-list">
                    @foreach($data['skills'] as $skill)
                    <li>
                        {{ is_array($skill) ? ($skill['name'] ?? '') : $skill }}
                        @if(is_array($skill) && isset($skill['level'])) — {{ $skill['level'] }} @endif
                    </li>
                    @endforeach
                </ul>
                @endif

                @if(!empty($data['languages']) && count($data['languages']) > 0)
                <h2>Langues</h2>
                @foreach($data['languages'] as $lang)
                <div class="language">
                    <span>{{ is_array($lang) ? ($lang['language_name'] ?? '') : $lang }}</span>
                    <span class="language-level">{{ is_array($lang) ? ($lang['language_level'] ?? '') : '' }}</span>
                </div>
                @endforeach
                @endif

                <h2>Contact</h2>

                <div class="contact-item">
                    <span class="contact-icon">@</span>
                    {{ $data['personal_info']['email'] ?? 'nom@email.com' }}
                </div>

                <div class="contact-item">
                    <span class="contact-icon">☎</span>
                    {{ $data['personal_info']['phone'] ?? '+229 00 00 00 00' }}
                </div>

                <div class="contact-item">
                    <span class="contact-icon">⚲</span>
                    {{ $data['personal_info']['address'] ?? 'Cotonou, Bénin' }}
                </div>

            </div>

        </div>

    </div>

</body>

</html>