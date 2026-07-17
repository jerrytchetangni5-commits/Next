<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>CV - {{ $data['personal_info']['first_name'] ?? '' }} {{ $data['personal_info']['last_name'] ?? '' }}</title>
    <style>
        :root {
            --cream: #F6F1E7;
            --sand: #DCC5A0;
            --sand-dark: #C7A876;
            --coffee: #8A6A47;
            --coffee-dark: #6B4F33;
            --maroon: #7A4B3A;
            --ink: #3A3226;
            --ink-soft: #6B6255;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Georgia', 'Times New Roman', serif;
            background: #EDE4D3;
            display: flex;
            justify-content: center;
            padding: 40px 20px;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            background: var(--cream);
            display: grid;
            grid-template-columns: 240px 1fr;
            box-shadow: 0 20px 60px rgba(107, 79, 51, 0.25);
            position: relative;
            overflow: hidden;
        }

        .page::after {
            content: "";
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 14px;
            background: linear-gradient(90deg, var(--sand-dark), var(--coffee));
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            background: var(--sand);
            padding: 48px 28px 60px;
            display: flex;
            flex-direction: column;
            gap: 34px;
        }

        .photo-wrap {
            width: 168px;
            height: 168px;
            border-radius: 50%;
            border: 3px solid var(--coffee-dark);
            padding: 6px;
            align-self: center;
        }
        .photo-wrap img,
        .photo-wrap .photo-placeholder {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            display: block;
            background: var(--cream);
        }
        .photo-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--ink-soft);
            font-size: 12px;
            text-align: center;
        }

        .sidebar-block h3 {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 11px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--coffee-dark);
            margin-bottom: 10px;
            padding-bottom: 6px;
            border-bottom: 1px solid var(--coffee-dark);
        }

        .contact-item {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 12.5px;
            color: var(--ink);
            margin-bottom: 12px;
            line-height: 1.5;
        }
        .contact-item span {
            display: block;
            font-size: 10px;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--ink-soft);
        }

        /* Compétences - sans barres, liste simple */
        .skill-item {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 12px;
            color: var(--ink);
            margin-bottom: 8px;
            padding-left: 14px;
            position: relative;
        }
        .skill-item::before {
            content: "▸";
            position: absolute;
            left: 0;
            color: var(--coffee-dark);
        }
        .skill-item .skill-level {
            color: var(--coffee-dark);
            font-weight: bold;
        }

        .lang-item {
            display: flex;
            justify-content: space-between;
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 12px;
            color: var(--ink);
            margin-bottom: 8px;
        }
        .lang-item .level { color: var(--coffee-dark); font-weight: bold; }

        /* ===== MAIN ===== */
        .main {
            padding: 50px 46px 60px;
        }

        .identity h1 {
            font-size: 34px;
            letter-spacing: 3px;
            color: var(--ink);
            text-transform: uppercase;
        }
        .identity .role {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 14px;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: var(--coffee);
            margin-top: 6px;
            padding-bottom: 18px;
            border-bottom: 2px solid var(--sand-dark);
            margin-bottom: 28px;
        }

        .section { margin-bottom: 30px; }

        .section-tag {
            display: inline-block;
            position: relative;
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 11.5px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #fff;
            background: var(--coffee);
            padding: 7px 20px 7px 16px;
            margin-bottom: 14px;
            clip-path: polygon(0 0, 92% 0, 100% 50%, 92% 100%, 0 100%);
        }

        .section p.text {
            font-size: 13px;
            line-height: 1.7;
            color: var(--ink-soft);
        }

        .entry {
            position: relative;
            padding-left: 22px;
            margin-bottom: 18px;
            border-left: 2px solid var(--sand-dark);
        }
        .entry::before {
            content: "";
            position: absolute;
            left: -6px; top: 3px;
            width: 10px; height: 10px;
            border-radius: 50%;
            background: var(--maroon);
            border: 2px solid var(--cream);
        }
        .entry .entry-title {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-weight: bold;
            font-size: 13px;
            color: var(--ink);
        }
        .entry .entry-meta {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 11px;
            color: var(--coffee);
            letter-spacing: 0.5px;
            margin: 2px 0 6px;
        }
        .entry .entry-desc {
            font-size: 12.5px;
            line-height: 1.6;
            color: var(--ink-soft);
        }

        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 11px;
            color: var(--ink-soft);
        }
        .signatures div { text-align: center; width: 160px; }
        .signatures .line { border-top: 1px dotted var(--ink-soft); margin-bottom: 6px; height: 30px; }

        @media print {
            body { background: none; padding: 0; }
            .page { box-shadow: none; }
        }
    </style>
</head>
<body>

    <div class="page">

        <!-- ===== SIDEBAR ===== -->
        <aside class="sidebar">

            <div class="photo-wrap">
                @if(!empty($data['personal_info']['photo']))
                    <img src="{{ $data['personal_info']['photo'] }}" alt="Photo">
                @else
                    <div class="photo-placeholder">Photo</div>
                @endif
            </div>

            <div class="sidebar-block">
                <h3>Contact</h3>
                <div class="contact-item">
                    <span>E-mail</span>
                    {{ $data['personal_info']['email'] ?? 'nom@exemple.com' }}
                </div>
                <div class="contact-item">
                    <span>Téléphone</span>
                    {{ $data['personal_info']['phone'] ?? '+229 00 00 00 00' }}
                </div>
                <div class="contact-item">
                    <span>Adresse</span>
                    {{ $data['personal_info']['address'] ?? 'Cotonou, Bénin' }}
                </div>
            </div>

            @if(!empty($data['skills']) && count($data['skills']) > 0)
            <div class="sidebar-block">
                <h3>Compétences</h3>
                @foreach($data['skills'] as $skill)
                    <div class="skill-item">
                        {{ is_array($skill) ? ($skill['name'] ?? '') : $skill }}
                        @if(is_array($skill) && isset($skill['level']))
                            <span class="skill-level">— {{ $skill['level'] }}</span>
                        @endif
                    </div>
                @endforeach
            </div>
            @endif

            @if(!empty($data['languages']) && count($data['languages']) > 0)
            <div class="sidebar-block">
                <h3>Langues</h3>
                @foreach($data['languages'] as $lang)
                    <div class="lang-item">
                        <span>{{ is_array($lang) ? ($lang['language_name'] ?? '') : $lang }}</span>
                        <span class="level">{{ is_array($lang) ? ($lang['language_level'] ?? '') : '' }}</span>
                    </div>
                @endforeach
            </div>
            @endif

        </aside>

        <!-- ===== MAIN ===== -->
        <main class="main">

            <div class="identity">
                <h1>{{ $data['personal_info']['first_name'] ?? 'Prénom' }} {{ $data['personal_info']['last_name'] ?? 'Nom' }}</h1>
                <div class="role">{{ $data['personal_info']['title'] ?? 'Titre du poste' }}</div>
            </div>

            @if(!empty($data['summary']))
            <section class="section">
                <div class="section-tag">Profil</div>
                <p class="text">{{ $data['summary'] }}</p>
            </section>
            @endif

            @if(!empty($data['educations']) && count($data['educations']) > 0)
            <section class="section">
                <div class="section-tag">Formations</div>
                @foreach($data['educations'] as $edu)
                    <div class="entry">
                        <div class="entry-title">{{ $edu['degree'] ?? '' }}</div>
                        <div class="entry-meta">
                            {{ $edu['school'] ?? '' }}
                            @if(!empty($edu['city'])) — {{ $edu['city'] }} @endif
                            · {{ $edu['start_date'] ?? '' }} – {{ $edu['end_date'] ?? '' }}
                        </div>
                        @if(!empty($edu['description']))
                            <div class="entry-desc">{{ $edu['description'] }}</div>
                        @endif
                    </div>
                @endforeach
            </section>
            @endif

            @if(!empty($data['experiences']) && count($data['experiences']) > 0)
            <section class="section">
                <div class="section-tag">Expériences</div>
                @foreach($data['experiences'] as $exp)
                    <div class="entry">
                        <div class="entry-title">
                            {{ $exp['position'] ?? '' }}
                            @if(!empty($exp['company'])) — {{ $exp['company'] }} @endif
                        </div>
                        <div class="entry-meta">
                            @if(!empty($exp['city'])) {{ $exp['city'] }} · @endif
                            {{ $exp['start_date'] ?? '' }} – {{ $exp['end_date'] ?? '' }}
                        </div>
                        @if(!empty($exp['description']))
                            <div class="entry-desc">{{ $exp['description'] }}</div>
                        @endif
                    </div>
                @endforeach
            </section>
            @endif

            <div class="signatures">
                <div>
                    <div class="line"></div>
                    Date
                </div>
                <div>
                    <div class="line"></div>
                    Signature
                </div>
            </div>

        </main>

    </div>

</body>
</html>