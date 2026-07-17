<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>CV - {{ $data['personal_info']['first_name'] ?? '' }} {{ $data['personal_info']['last_name'] ?? '' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy: #14284A;
            --accent: #2E63B8;
            --accent-soft: #5C8FDD;
            --ink: #1E2430;
            --body-text: #4A505B;
            --muted: #A7B2C6;
            --rule: #E4E7EC;
            --panel: #FFFFFF;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: #EDEDED;
            font-family: 'Poppins', Arial, Helvetica, sans-serif;
            display: flex;
            justify-content: center;
            padding: 40px;
        }

        .cv {
            width: 210mm;
            min-height: 297mm;
            background: var(--panel);
            box-shadow: 0 20px 60px rgba(0, 0, 0, .2);
            display: grid;
            grid-template-columns: 250px 1fr;
            overflow: hidden;
        }

        /* ===================== SIDEBAR ===================== */
        .sidebar {
            background: var(--navy);
            color: #fff;
            padding: 48px 30px 40px;
        }

        .photo-frame {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: #D6DBE2;
            border: 6px solid rgba(255, 255, 255, .9);
            margin: 0 auto 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .photo-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .photo-frame svg { width: 72px; height: 72px; color: #7C8798; }

        .side-block { margin-bottom: 36px; }
        .side-block:last-child { margin-bottom: 0; }

        .side-heading {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }
        .side-heading h2 {
            font-size: 15.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .icon-badge {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: 1.4px solid rgba(255, 255, 255, .55);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .icon-badge svg { width: 15px; height: 15px; color: #fff; }
        .icon-badge.sm { width: 25px; height: 25px; }
        .icon-badge.sm svg { width: 12px; height: 12px; }

        .about-text {
            font-size: 12.5px;
            line-height: 1.75;
            color: var(--muted);
            font-weight: 300;
        }

        .contact-row {
            display: flex;
            align-items: center;
            gap: 11px;
            font-size: 11.8px;
            margin-bottom: 14px;
            line-height: 1.4;
            word-break: break-word;
        }
        .contact-row:last-child { margin-bottom: 0; }

        /* Skills - sans barres, liste simple */
        .skill-item {
            font-size: 12.5px;
            color: #c8d6e5;
            margin-bottom: 10px;
            padding-left: 16px;
            position: relative;
        }
        .skill-item::before {
            content: "•";
            position: absolute;
            left: 0;
            color: var(--accent-soft);
        }
        .skill-item .skill-level {
            color: var(--muted);
            font-size: 9.5px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .lang-item {
            font-size: 13px;
            padding-left: 15px;
            position: relative;
            margin-bottom: 12px;
            display: flex;
            justify-content: space-between;
            gap: 8px;
        }
        .lang-item:last-child { margin-bottom: 0; }
        .lang-item::before {
            content: "•";
            position: absolute;
            left: 0;
            color: #fff;
        }
        .lang-level {
            color: var(--muted);
            font-size: 10.5px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
            white-space: nowrap;
        }

        /* ===================== MAIN ===================== */
        .main { padding: 50px 46px 50px 42px; }

        .main-header { margin-bottom: 34px; }

        .name {
            font-size: 42px;
            line-height: 1.05;
            font-weight: 800;
            text-transform: uppercase;
        }
        .name .a { color: var(--ink); }
        .name .b { color: var(--accent); }

        .role {
            margin-top: 14px;
            font-size: 14px;
            letter-spacing: 4px;
            font-weight: 600;
            color: #6B7280;
            text-transform: uppercase;
        }

        .quick-contact {
            display: flex;
            gap: 18px;
            margin-top: 22px;
            flex-wrap: wrap;
        }
        .quick-contact-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 11.5px;
            color: var(--body-text);
            white-space: nowrap;
        }
        .quick-contact-item .icon-badge { border-color: var(--accent); }
        .quick-contact-item .icon-badge svg { color: var(--accent); }

        .header-rule {
            height: 1px;
            background: var(--rule);
            margin-top: 26px;
        }

        .section { margin-top: 38px; }
        .section:first-of-type { margin-top: 0; }

        .section-heading {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
        }
        .section-heading h2 {
            font-size: 19px;
            font-weight: 700;
            color: var(--ink);
            text-transform: uppercase;
            letter-spacing: .5px;
        }
        .icon-badge-lg {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: var(--navy);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .icon-badge-lg svg { width: 20px; height: 20px; color: #fff; }

        .tl { position: relative; padding-left: 28px; }
        .tl.multi::before {
            content: "";
            position: absolute;
            left: 5px;
            top: 8px;
            bottom: 8px;
            width: 2px;
            background: var(--rule);
        }

        .titem { position: relative; margin-bottom: 26px; }
        .titem:last-child { margin-bottom: 0; }
        .titem::before {
            content: "";
            position: absolute;
            left: -28px;
            top: 3px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--navy);
        }

        .titem .degree,
        .titem .role-title {
            font-size: 14.5px;
            font-weight: 700;
            color: var(--ink);
            text-transform: uppercase;
            letter-spacing: .3px;
        }
        .titem .institution,
        .titem .meta {
            font-size: 12.5px;
            font-weight: 600;
            color: var(--accent);
            margin-top: 4px;
            margin-bottom: 9px;
        }
        .titem .desc {
            font-size: 12.5px;
            line-height: 1.65;
            color: var(--body-text);
        }
        .desc-list { list-style: none; }
        .desc-list li {
            font-size: 12.5px;
            line-height: 1.6;
            color: var(--body-text);
            padding-left: 14px;
            position: relative;
            margin-bottom: 5px;
        }
        .desc-list li::before {
            content: "•";
            position: absolute;
            left: 0;
            color: var(--accent);
        }
        .desc-list li:last-child { margin-bottom: 0; }

        @media print {
            body { padding: 0; background: #fff; }
            .cv { box-shadow: none; }
        }
    </style>
</head>
<body>

<div class="cv">

    <aside class="sidebar">

        <div class="photo-frame">
            @if(!empty($data['personal_info']['photo']))
                <img src="{{ $data['personal_info']['photo'] }}" alt="Photo">
            @else
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="3.4"/><path d="M4.8 20c0-4 3.2-6.8 7.2-6.8s7.2 2.8 7.2 6.8"/></svg>
            @endif
        </div>

        @if(!empty($data['summary']))
        <div class="side-block">
            <div class="side-heading">
                <span class="icon-badge"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="3.2"/><path d="M5 20c0-3.9 3.13-6.5 7-6.5s7 2.6 7 6.5"/></svg></span>
                <h2>À propos</h2>
            </div>
            <p class="about-text">{{ $data['summary'] }}</p>
        </div>
        @endif

        <div class="side-block">
            <div class="side-heading">
                <span class="icon-badge"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3.5" y="6" width="17" height="12" rx="1.6"/><path d="M4 7.5l8 5.5 8-5.5"/></svg></span>
                <h2>Contact</h2>
            </div>
            <div class="contact-row">
                <span class="icon-badge sm"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3.5" y="6" width="17" height="12" rx="1.6"/><path d="M4 7.5l8 5.5 8-5.5"/></svg></span>
                <span>{{ $data['personal_info']['email'] ?? 'nom@email.com' }}</span>
            </div>
            <div class="contact-row">
                <span class="icon-badge sm"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M7.1 10.6c1.2 2.4 3.3 4.5 5.7 5.7l1.9-1.9c.3-.3.7-.4 1.1-.2 1 .4 2 .5 3 .5.6 0 1 .4 1 1V19c0 .6-.4 1-1 1C10.6 20 4 13.4 4 6c0-.6.4-1 1-1h2.9c.6 0 1 .4 1 1 0 1 .2 2 .5 3 .1.4 0 .8-.2 1.1L7.1 10.6z"/></svg></span>
                <span>{{ $data['personal_info']['phone'] ?? '+229 00 00 00 00' }}</span>
            </div>
            <div class="contact-row">
                <span class="icon-badge sm"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s6.5-6 6.5-10.5a6.5 6.5 0 1 0-13 0C5.5 15 12 21 12 21z"/><circle cx="12" cy="10.3" r="2.3"/></svg></span>
                <span>{{ $data['personal_info']['address'] ?? 'Cotonou, Bénin' }}</span>
            </div>
        </div>

        @if(!empty($data['skills']) && count($data['skills']) > 0)
        <div class="side-block">
            <div class="side-heading">
                <span class="icon-badge"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 19V11"/><path d="M12 19V5"/><path d="M19 19v-6"/></svg></span>
                <h2>Compétences</h2>
            </div>

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
        <div class="side-block">
            <div class="side-heading">
                <span class="icon-badge"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="8.5"/><path d="M3.5 12h17"/><path d="M12 3.5c2.2 2.3 3.4 5.2 3.4 8.5s-1.2 6.2-3.4 8.5c-2.2-2.3-3.4-5.2-3.4-8.5S9.8 5.8 12 3.5z"/></svg></span>
                <h2>Langues</h2>
            </div>
            @foreach($data['languages'] as $lang)
            <div class="lang-item">
                <span>{{ is_array($lang) ? ($lang['language_name'] ?? '') : $lang }}</span>
                <span class="lang-level">{{ is_array($lang) ? ($lang['language_level'] ?? '') : '' }}</span>
            </div>
            @endforeach
        </div>
        @endif

    </aside>

    <main class="main">

        <header class="main-header">
            <h1 class="name">
                <span class="a">{{ $data['personal_info']['first_name'] ?? 'Prénom' }}</span><br>
                <span class="b">{{ $data['personal_info']['last_name'] ?? 'Nom' }}</span>
            </h1>
            <div class="role">{{ $data['personal_info']['title'] ?? 'Développeur Full Stack' }}</div>

            <div class="quick-contact">
                <div class="quick-contact-item">
                    <span class="icon-badge sm"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s6.5-6 6.5-10.5a6.5 6.5 0 1 0-13 0C5.5 15 12 21 12 21z"/><circle cx="12" cy="10.3" r="2.3"/></svg></span>
                    <span>{{ $data['personal_info']['address'] ?? 'Cotonou, Bénin' }}</span>
                </div>
                <div class="quick-contact-item">
                    <span class="icon-badge sm"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M7.1 10.6c1.2 2.4 3.3 4.5 5.7 5.7l1.9-1.9c.3-.3.7-.4 1.1-.2 1 .4 2 .5 3 .5.6 0 1 .4 1 1V19c0 .6-.4 1-1 1C10.6 20 4 13.4 4 6c0-.6.4-1 1-1h2.9c.6 0 1 .4 1 1 0 1 .2 2 .5 3 .1.4 0 .8-.2 1.1L7.1 10.6z"/></svg></span>
                    <span>{{ $data['personal_info']['phone'] ?? '+229 00 00 00 00' }}</span>
                </div>
                <div class="quick-contact-item">
                    <span class="icon-badge sm"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3.5" y="6" width="17" height="12" rx="1.6"/><path d="M4 7.5l8 5.5 8-5.5"/></svg></span>
                    <span>{{ $data['personal_info']['email'] ?? 'nom@email.com' }}</span>
                </div>
            </div>

            <div class="header-rule"></div>
        </header>

        @if(!empty($data['educations']) && count($data['educations']) > 0)
        <section class="section">
            <div class="section-heading">
                <span class="icon-badge-lg"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 8.5 12 4l10 4.5-10 4.5-10-4.5z"/><path d="M6.5 10.7v4.3c0 1.5 2.5 2.7 5.5 2.7s5.5-1.2 5.5-2.7v-4.3"/><path d="M21 8.5v6"/></svg></span>
                <h2>Formations</h2>
            </div>
            <div class="tl">
                @foreach($data['educations'] as $edu)
                <div class="titem">
                    <div class="degree">{{ $edu['degree'] ?? '' }}</div>
                    <div class="institution">
                        {{ $edu['school'] ?? '' }}
                        @if(!empty($edu['start_date']) || !empty($edu['end_date']))
                            &nbsp;·&nbsp;{{ $edu['start_date'] ?? '' }} – {{ $edu['end_date'] ?? '' }}
                        @endif
                    </div>
                    @if(!empty($edu['description']))
                        <p class="desc">{{ $edu['description'] }}</p>
                    @endif
                </div>
                @endforeach
            </div>
        </section>
        @endif

        @if(!empty($data['experiences']) && count($data['experiences']) > 0)
        <section class="section">
            <div class="section-heading">
                <span class="icon-badge-lg"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="7.5" width="18" height="12" rx="1.8"/><path d="M8.5 7.5V5.8c0-.7.6-1.3 1.3-1.3h4.4c.7 0 1.3.6 1.3 1.3V7.5"/><path d="M3 12.5h18"/></svg></span>
                <h2>Expériences</h2>
            </div>
            <div class="tl multi">
                @foreach($data['experiences'] as $exp)
                <div class="titem">
                    <div class="role-title">{{ $exp['position'] ?? '' }}</div>
                    <div class="meta">
                        {{ $exp['company'] ?? '' }}
                        @if(!empty($exp['start_date']) || !empty($exp['end_date']))
                            &nbsp;·&nbsp;{{ $exp['start_date'] ?? '' }} – {{ $exp['end_date'] ?? '' }}
                        @endif
                    </div>
                    @if(!empty($exp['description']))
                        <ul class="desc-list">
                            @foreach(explode("\n", $exp['description']) as $line)
                                @if(trim($line))
                                    <li>{{ trim($line) }}</li>
                                @endif
                            @endforeach
                        </ul>
                    @endif
                </div>
                @endforeach
            </div>
        </section>
        @endif

    </main>

</div>

</body>
</html>