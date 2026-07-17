<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>CV - {{ $data['personal_info']['first_name'] ?? '' }} {{ $data['personal_info']['last_name'] ?? '' }}</title>

    <style>
        :root {
            --bordeaux: #7A1F27;
            --bordeaux-dark: #5E171E;
            --text-dark: #2B2B2B;
            --soft: #6B6B6B;
            --line: #C9A9AC;
            --white: #FFFFFF;
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
            background: var(--white);
            box-shadow: 0 20px 60px rgba(0, 0, 0, .2);
            display: grid;
            grid-template-columns: 235px 1fr;
            overflow: hidden;
        }

        /* SIDEBAR */
        .sidebar {
            background: var(--bordeaux);
            color: var(--white);
            padding: 45px 30px 50px 30px;
        }

        .photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
            background: #E7CFCF;
            border: 4px solid var(--white);
            margin: 0 auto 40px auto;
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
            color: var(--bordeaux);
            font-size: 12px;
        }

        .side-title {
            font-size: 19px;
            font-weight: 800;
            padding-bottom: 8px;
            border-bottom: 1px solid rgba(255, 255, 255, .5);
            margin-bottom: 16px;
        }

        .side-section {
            margin-bottom: 34px;
        }

        .field {
            margin-bottom: 16px;
        }

        .field-label {
            font-size: 13px;
            font-weight: 800;
            margin-bottom: 4px;
        }

        .field-value {
            font-size: 12.5px;
            line-height: 1.5;
            color: #EAD9DA;
        }

        .skills-list {
            list-style: none;
            font-size: 12.5px;
        }

        .skills-list li {
            padding-left: 14px;
            position: relative;
            margin-bottom: 9px;
            color: #EAD9DA;
        }

        .skills-list li::before {
            content: "•";
            position: absolute;
            left: 0;
            color: var(--white);
        }

        .language-item {
            font-size: 12.5px;
            margin-bottom: 10px;
            color: #EAD9DA;
        }

        .language-item b {
            color: var(--white);
        }

        /* MAIN */
        main {
            padding: 50px 45px 50px 45px;
        }

        .name {
            font-size: 36px;
            font-weight: 800;
            color: var(--bordeaux);
            margin-bottom: 36px;
        }

        .section-title {
            font-size: 20px;
            font-weight: 800;
            color: var(--bordeaux);
            padding-bottom: 8px;
            border-bottom: 2px solid var(--bordeaux);
            margin-bottom: 22px;
        }

        .job {
            display: grid;
            grid-template-columns: 130px 1fr;
            gap: 18px;
            margin-bottom: 26px;
        }

        .job-date {
            font-size: 12px;
            color: var(--soft);
            font-weight: bold;
        }

        .job-company {
            font-size: 12.5px;
            color: var(--soft);
            margin-top: 6px;
            line-height: 1.4;
        }

        .job-title {
            font-size: 14.5px;
            color: var(--text-dark);
            font-weight: bold;
            margin-bottom: 6px;
        }

        .job ul {
            padding-left: 16px;
        }

        .job ul li {
            font-size: 12.5px;
            color: var(--soft);
            line-height: 1.6;
            margin-bottom: 4px;
        }

        .edu-block {
            margin-bottom: 16px;
        }

        .edu-block h3 {
            font-size: 14px;
            color: var(--text-dark);
        }

        .edu-block p {
            font-size: 12.5px;
            color: var(--soft);
            margin-top: 3px;
        }

        .section-gap {
            margin-top: 38px;
        }

        .projects-text {
            font-size: 12.5px;
            color: var(--soft);
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

        <aside class="sidebar">

            <div class="photo">
                @if(!empty($data['personal_info']['photo']))
                    <img src="{{ $data['personal_info']['photo'] }}" alt="Photo">
                @else
                    <div class="photo-placeholder">Photo</div>
                @endif
            </div>

            <div class="side-section">
                <div class="side-title">Contact</div>

                <div class="field">
                    <div class="field-label">Téléphone</div>
                    <div class="field-value">{{ $data['personal_info']['phone'] ?? '+229 00 00 00 00' }}</div>
                </div>

                <div class="field">
                    <div class="field-label">Email</div>
                    <div class="field-value">{{ $data['personal_info']['email'] ?? 'nom@email.com' }}</div>
                </div>

                <div class="field">
                    <div class="field-label">Adresse</div>
                    <div class="field-value">{{ $data['personal_info']['address'] ?? 'Cotonou, Bénin' }}</div>
                </div>
            </div>

            @if(!empty($data['skills']) && count($data['skills']) > 0)
            <div class="side-section">
                <div class="side-title">Compétences</div>

                <ul class="skills-list">
                    @foreach($data['skills'] as $skill)
                    <li>
                        {{ is_array($skill) ? ($skill['name'] ?? '') : $skill }}
                        @if(is_array($skill) && isset($skill['level']))
                            ({{ $skill['level'] }})
                        @endif
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if(!empty($data['languages']) && count($data['languages']) > 0)
            <div class="side-section">
                <div class="side-title">Langues</div>

                @foreach($data['languages'] as $lang)
                <div class="language-item">
                    <b>{{ is_array($lang) ? ($lang['language_name'] ?? '') : $lang }}</b>
                    — {{ is_array($lang) ? ($lang['language_level'] ?? '') : '' }}
                </div>
                @endforeach
            </div>
            @endif

        </aside>

        <main>

            <h1 class="name">{{ $data['personal_info']['first_name'] ?? 'Prénom' }} {{ $data['personal_info']['last_name'] ?? 'Nom' }}</h1>

            @if(!empty($data['experiences']) && count($data['experiences']) > 0)
            <div class="section">
                <div class="section-title">Expériences</div>

                @foreach($data['experiences'] as $exp)
                <div class="job">

                    <div>
                        <div class="job-date">
                            {{ $exp['start_date'] ?? '' }} – {{ $exp['end_date'] ?? '' }}
                        </div>
                        <div class="job-company">{{ $exp['company'] ?? 'Entreprise' }}</div>
                    </div>

                    <div>
                        <div class="job-title">{{ $exp['position'] ?? '' }}</div>
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

                </div>
                @endforeach

            </div>
            @endif

            @if(!empty($data['educations']) && count($data['educations']) > 0)
            <div class="section section-gap">
                <div class="section-title">Formations</div>

                @foreach($data['educations'] as $edu)
                <div class="edu-block">
                    <h3>{{ $edu['school'] ?? 'Université' }}</h3>
                    <p>
                        {{ $edu['degree'] ?? '' }}
                        @if(!empty($edu['start_date']) || !empty($edu['end_date']))
                            — {{ $edu['start_date'] ?? '' }} – {{ $edu['end_date'] ?? '' }}
                        @endif
                    </p>
                </div>
                @endforeach

            </div>
            @endif

            @if(!empty($data['interests']) && count($data['interests']) > 0)
            <div class="section section-gap">
                <div class="section-title">Projets & Intérêts</div>

                <p class="projects-text">
                    @foreach($data['interests'] as $interest)
                        {{ is_array($interest) ? ($interest['name'] ?? '') : $interest }}
                        @if(!$loop->last), @endif
                    @endforeach
                </p>
            </div>
            @endif

        </main>

    </div>

</body>

</html>