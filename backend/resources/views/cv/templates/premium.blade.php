<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>CV Premium - {{ $data['personal_info']['first_name'] ?? '' }} {{ $data['personal_info']['last_name'] ?? '' }}</title>

    <style>
        :root {
            --background: #F4F6F8;
            --paper: #FFFFFF;
            --primary: #243447;
            --secondary: #4F6D7A;
            --accent: #C06C84;
            --text: #1F2933;
            --soft: #6B7280;
            --line: #D9E2EC;
            --progress: #243447;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: var(--background);
            font-family: "Inter", Arial, sans-serif;
            display: flex;
            justify-content: center;
            padding: 40px;
        }

        .cv {
            width: 210mm;
            min-height: 297mm;
            background: var(--paper);
            box-shadow: 0 20px 60px rgba(36, 52, 71, .15);
            padding: 50px;
            position: relative;
            overflow: hidden;
        }

        /* Décoration */
        .cv::before {
            content: "";
            position: absolute;
            width: 250px;
            height: 250px;
            background: var(--accent);
            opacity: .08;
            right: -100px;
            top: -100px;
            border-radius: 50%;
        }

        .cv::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            height: 10px;
            width: 100%;
            background: linear-gradient(90deg, var(--primary), var(--accent));
        }

        /* HEADER */
        .header {
            display: grid;
            grid-template-columns: 150px 1fr;
            gap: 35px;
            padding-bottom: 35px;
            border-bottom: 1px solid var(--line);
        }

        .photo {
            width: 140px;
            height: 160px;
            border-radius: 12px;
            overflow: hidden;
            background: #F1F5F9;
            border: 5px solid white;
            box-shadow: 0 8px 20px rgba(0, 0, 0, .1);
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

        .name {
            font-size: 40px;
            letter-spacing: 4px;
            color: var(--primary);
            text-transform: uppercase;
            font-weight: 800;
        }

        .role {
            margin-top: 12px;
            color: var(--accent);
            letter-spacing: 3px;
            font-size: 14px;
            text-transform: uppercase;
            font-weight: bold;
        }

        .summary {
            margin-top: 22px;
            color: var(--soft);
            font-size: 13px;
            line-height: 1.8;
        }

        /* CONTENU */
        .content {
            display: grid;
            grid-template-columns: 1fr 260px;
            gap: 45px;
            margin-top: 40px;
        }

        /* SECTIONS */
        .section {
            margin-bottom: 35px;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 15px;
            color: var(--primary);
            font-size: 12px;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .section-title::after {
            content: "";
            height: 1px;
            background: var(--line);
            flex: 1;
        }

        /* EXPERIENCE / FORMATION */
        .item {
            margin-bottom: 25px;
            padding-left: 20px;
            border-left: 2px solid var(--line);
            position: relative;
        }

        .item::before {
            content: "";
            position: absolute;
            left: -6px;
            top: 4px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--accent);
        }

        .item h3 {
            font-size: 15px;
            color: var(--text);
        }

        .meta {
            color: var(--secondary);
            font-size: 12px;
            margin: 6px 0;
        }

        .description {
            color: var(--soft);
            font-size: 13px;
            line-height: 1.6;
        }

        /* SIDEBAR */
        .sidebar {
            border-left: 1px solid var(--line);
            padding-left: 30px;
        }

        .contact {
            color: var(--text);
            font-size: 13px;
            line-height: 2;
        }

        .label {
            color: var(--secondary);
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        /* COMPETENCES - sans barres, liste simple */
        .skill-item {
            font-size: 12.5px;
            color: var(--text);
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
        .skill-item .skill-level {
            color: var(--accent);
            font-size: 11px;
            font-weight: bold;
        }

        /* LANGUES */
        .language {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            margin-bottom: 14px;
        }

        .level {
            color: var(--accent);
            font-weight: bold;
            text-transform: uppercase;
        }

        @media print {
            body { padding: 0; background: white; }
            .cv { box-shadow: none; }
        }
    </style>
</head>

<body>

    <div class="cv">

        <header class="header">

            <div class="photo">
                @if(!empty($data['personal_info']['photo']))
                    <img src="{{ $data['personal_info']['photo'] }}" alt="Photo">
                @else
                    <div class="photo-placeholder">Photo</div>
                @endif
            </div>

            <div>
                <h1 class="name">
                    {{ $data['personal_info']['first_name'] ?? 'Prénom' }} {{ $data['personal_info']['last_name'] ?? 'Nom' }}
                </h1>

                <div class="role">
                    {{ $data['personal_info']['title'] ?? 'Développeur Full Stack' }}
                </div>

                @if(!empty($data['summary']))
                    <p class="summary">
                        {{ $data['summary'] }}
                    </p>
                @endif
            </div>

        </header>

        <div class="content">

            <main>

                @if(!empty($data['experiences']) && count($data['experiences']) > 0)
                <section class="section">
                    <div class="section-title">Expériences</div>

                    @foreach($data['experiences'] as $exp)
                    <div class="item">
                        <h3>{{ $exp['position'] ?? '' }}</h3>
                        <div class="meta">
                            {{ $exp['company'] ?? '' }}
                            @if(!empty($exp['city'])) | {{ $exp['city'] }} @endif
                            | {{ $exp['start_date'] ?? '' }} - {{ $exp['end_date'] ?? '' }}
                        </div>
                        @if(!empty($exp['description']))
                            <p class="description">{{ $exp['description'] }}</p>
                        @endif
                    </div>
                    @endforeach
                </section>
                @endif

                @if(!empty($data['educations']) && count($data['educations']) > 0)
                <section class="section">
                    <div class="section-title">Formations</div>

                    @foreach($data['educations'] as $edu)
                    <div class="item">
                        <h3>{{ $edu['degree'] ?? '' }}</h3>
                        <div class="meta">
                            {{ $edu['school'] ?? '' }}
                            @if(!empty($edu['city'])) | {{ $edu['city'] }} @endif
                            | {{ $edu['start_date'] ?? '' }} - {{ $edu['end_date'] ?? '' }}
                        </div>
                        @if(!empty($edu['description']))
                            <p class="description">{{ $edu['description'] }}</p>
                        @endif
                    </div>
                    @endforeach
                </section>
                @endif

                @if(!empty($data['interests']) && count($data['interests']) > 0)
                <section class="section">
                    <div class="section-title">Centres d'intérêt</div>
                    <p class="description">
                        @foreach($data['interests'] as $interest)
                            {{ is_array($interest) ? ($interest['name'] ?? '') : $interest }}
                            @if(!$loop->last), @endif
                        @endforeach
                    </p>
                </section>
                @endif

            </main>

            <aside class="sidebar">

                <section class="section">
                    <div class="section-title">Contact</div>
                    <div class="contact">
                        <span class="label">Email</span><br>
                        {{ $data['personal_info']['email'] ?? 'nom@email.com' }}
                        <br><br>
                        <span class="label">Téléphone</span><br>
                        {{ $data['personal_info']['phone'] ?? '+229 00 00 00 00' }}
                        <br><br>
                        <span class="label">Adresse</span><br>
                        {{ $data['personal_info']['address'] ?? 'Cotonou, Bénin' }}
                    </div>
                </section>

                @if(!empty($data['skills']) && count($data['skills']) > 0)
                <section class="section">
                    <div class="section-title">Compétences</div>

                    @foreach($data['skills'] as $skill)
                    <div class="skill-item">
                        {{ is_array($skill) ? ($skill['name'] ?? '') : $skill }}
                        @if(is_array($skill) && isset($skill['level']))
                            <span class="skill-level">— {{ $skill['level'] }}</span>
                        @endif
                    </div>
                    @endforeach
                </section>
                @endif

                @if(!empty($data['languages']) && count($data['languages']) > 0)
                <section class="section">
                    <div class="section-title">Langues</div>

                    @foreach($data['languages'] as $lang)
                    <div class="language">
                        <span>{{ is_array($lang) ? ($lang['language_name'] ?? '') : $lang }}</span>
                        <span class="level">{{ is_array($lang) ? ($lang['language_level'] ?? '') : '' }}</span>
                    </div>
                    @endforeach
                </section>
                @endif

            </aside>

        </div>

    </div>

</body>

</html>