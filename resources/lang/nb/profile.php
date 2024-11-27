<?php

return [

    'enable' => [
        'title' => 'Aktiver tofaktoroppsett',

        'intro' => 'Når tofaktorautentisering er aktivert, vil du bli bedt om et token under autentisering. Du kan hente dette tokenet fra telefonens autentiseringsapplikasjon, som Google Authenticator eller passordbehandlere som 1Password.',

        'enable' => 'Sett opp tofaktoroppsett',
    ],

    'locked' => [
        'title' => 'Kontoen er låst',

        'intro' => 'Denne brukerens konto har blitt låst på grunn av for mange mislykkede tofaktorautentiseringsforsøk.',

        'unlock' => 'Lås opp konto',

        'confirm_title' => 'Lås opp konto?',
        'confirm_1' => 'Å låse opp denne kontoen vil tillate brukeren å prøve å logge inn igjen.',
        'confirm_2' => 'Det kan også være nyttig å få dem til å tilbakestille passordet sitt, og til og med tilbakestille tofaktorautentiseringsoppsettet som en forsiktighetsregel.',
        'confirm_3' => 'Er du sikker på at du vil gjøre dette?',

        'success' => 'Kontoen er låst opp.',
    ],

    'recovery_codes' => [
        'title' => 'Gjenopprettingskoder',

        'intro' => 'Når tofaktorautentisering er aktivert, vil du bli bedt om et token under autentisering. Du kan hente dette tokenet fra telefonens autentiseringsapplikasjon, som Google Authenticator eller passordbehandlere som 1Password.',

        'codes' => [
            'new' => 'Dine nye gjenopprettingskoder',
            'show' => 'Dine gjenopprettingskoder',

            'footnote' => 'Oppbevar disse gjenopprettingskodene i en sikker passordbehandler. De kan brukes til å gjenopprette tilgangen til kontoen din hvis tofaktorautentiseringsenheten din går tapt.',
        ],

        'regenerate' => [
            'action' => 'Opprett nye gjenopprettingskoder',

            'confirm' => [
                'title' => 'Er du sikker?',
                'body_1' => 'Å regenerere gjenopprettingskodene dine vil ugyldiggjøre alle tidligere opprettede gjenopprettingskoder med umiddelbar virkning.',
                'body_2' => 'Er du sikker på at du vil gjøre dette?',
            ],

            'success' => '',
        ],

        'show' => [
            'action' => 'Vis gjenopprettingskoder',
        ],
    ],

    'reset' => [
        'title' => 'Tilbakestill tofaktoroppsett',

        'me_intro_1' => 'Dette vil fjerne alle tofaktorautentiseringsdetaljer fra kontoen din.',
        'me_intro_2' => 'Du kan sette opp tofaktorautentisering igjen fra profilen din i kontrollpanelet.',

        'me_enforced_intro_1' => 'Dette vil fjerne alle tofaktorautentiseringsdetaljer fra kontoen din og logge deg ut.',
        'me_enforced_intro_2' => 'Neste gang du logger på, må du sette opp tofaktorautentisering igjen før du får tilgang til kontrollpanelet.',

        'user_intro_1' => 'Dette vil fjerne alle tofaktorautentiseringsdetaljer fra kontoen deres.',
        'user_intro_2' => 'De vil kunne aktivere tofaktorautentisering fra sin profil i kontrollpanelet.',

        'user_enforced_intro_1' => 'Dette vil fjerne alle tofaktorautentiseringsdetaljene fra kontoen deres, og logge deg ut.',
        'user_enforced_intro_2' => 'Neste gang de logger på, må de sette opp tofaktorautentisering igjen før de får tilgang til kontrollpanelet.',

        'confirm' => [
            'title' => 'Er du sikker?',

            'me_1' => 'Dette vil fjerne alle tofaktorautentiseringsdetaljer fra kontoen din.',
            'me_2' => 'Du kan sette opp tofaktorautentisering fra profilen din i kontrollpanelet.',
            'me_3' => 'Er du sikker på at du vil gjøre dette?',

            'me_enforced_1' => 'Dette vil fjerne alle tofaktorautentiseringsdetaljene fra kontoen din og logge deg av <strong>umiddelbart</strong>.',
            'me_enforced_2' => 'Du må sette opp tofaktorautentisering ved neste pålogging.',
            'me_enforced_3' => 'Er du sikker på at du vil gjøre dette?',

            'user_1' => 'Dette vil fjerne alle tofaktorautentiseringsdetaljer fra kontoen deres.',
            'user_2' => 'De kan sette opp tofaktorautentisering fra sin profilside i kontrollpanelet.',
            'user_3' => 'Er du sikker på at du vil gjøre dette?',

            'user_enforced_1' => 'Dette vil fjerne alle tofaktorautentiseringsdetaljene fra kontoen deres, og logge dem ut ved neste besøk.',
            'user_enforced_2' => 'De må sette opp tofaktorautentisering ved neste pålogging.',
            'user_enforced_3' => 'Er du sikker på at du vil gjøre dette?',
        ],

        'action' => 'Tilbakestill tofaktoroppsett',

        'success' => 'Tofaktorstatusen er tilbakestilt.',
    ],

    'messages' => [
        'wrong_view' => 'Tofaktorfeltet kan kun brukes på "Rediger brukere"-visningen.',

        'not_setup_1' => 'Tofaktorautentisering er ikke satt opp ennå.',
        'not_setup_2' => 'Du kan administrere tofaktorautentiseringsdetaljer etter at brukeren har fullført oppsettet.',

        'not_enabled' => 'Tofaktorautentisering er ikke aktivert for dette nettstedet.',
    ],
];
