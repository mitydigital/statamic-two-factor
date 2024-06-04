<?php

return [

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

        'intro' => 'Når tofaktorautentisering er aktivert, vil du bli bedt om et sikkert, tilfeldig token under autentisering. Du kan hente dette tokenet fra telefonens autentiseringsapplikasjon, som Google Authenticator eller passordbehandlere som 1Password.',

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

        'me_intro_1' => 'Dette vil fjerne alle tofaktorautentiseringsdetaljer fra kontoen din, og logge deg ut.',
        'me_intro_2' => 'Neste gang du logger inn, må du sette opp tofaktorautentisering på nytt før du kan få tilgang til kontrollpanelet.',

        'user_intro_1' => 'Dette vil fjerne alle tofaktorautentiseringsdetaljer fra kontoen din, og logge deg ut.',
        'user_intro_2' => 'Neste gang du logger inn, må du sette opp tofaktorautentisering på nytt før du kan få tilgang til kontrollpanelet.',

        'confirm' => [
            'title' => 'Er du sikker?',

            'me_1' => 'Dette vil fjerne alle tofaktorautentiseringsdetaljer fra kontoen din, og logge deg ut <strong>umiddelbart</strong>. Du må sette opp tofaktorautentisering ved neste innlogging.',
            'me_2' => 'Er du virkelig sikker på at du vil gjøre dette?',

            'user_1' => 'Dette vil fjerne alle tofaktorautentiseringsdetaljer fra kontoen din, og logge deg ut ved neste besøk. Du må sette opp tofaktorautentisering ved neste innlogging.',
            'user_2' => 'Er du virkelig sikker på at du vil gjøre dette?',
        ],

        'action' => 'Tilbakestill tofaktoroppsett',

        'success' => 'Tofaktorstatusen er tilbakestilt.',
    ],

    'messages' => [
        'wrong_view' => 'Tofaktorfeltet kan kun brukes på "Rediger brukere"-visningen.',

        'not_setup' => 'Tofaktorautentisering er ikke satt opp ennå. Du kan administrere tofaktorautentiseringsdetaljer etter at brukeren har fullført oppsettet.',

        'not_enabled' => 'Tofaktorautentisering er ikke aktivert for dette nettstedet.',
    ],
];
