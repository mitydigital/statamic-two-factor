<?php

return [

    'enable' => [
        'title' => 'Stel tweestapsverificatie in',

        'intro' => 'Wanneer tweestapsverificatie is ingeschakeld, wordt je tijdens de verificatie gevraagd om een token in te voeren. Je kunt deze token verkrijgen via de authenticatie-app op je telefoon, zoals Google Authenticator of wachtwoordbeheerders zoals 1Password.',

        'enable' => 'Stel tweestapsverificatie in',
    ],

    'locked' => [
        'title' => 'Account is vergrendeld',

        'intro' => 'Het account van deze gebruiker is vergrendeld vanwege te veel mislukte pogingen voor tweestapsverificatie.',

        'unlock' => 'Ontgrendel account',

        'confirm_title' => 'Account ontgrendelen?',
        'confirm_1' => 'Het ontgrendelen van dit account stelt de gebruiker in staat om opnieuw in te loggen.',
        'confirm_2' => 'Het kan ook nuttig zijn om hen hun wachtwoord opnieuw te laten instellen, en zelfs hun tweestapsverificatie als voorzorgsmaatregel opnieuw in te stellen.',
        'confirm_3' => 'Weet je zeker dat je dit wilt doen?',

        'success' => 'Account is ontgrendeld.',
    ],

    'recovery_codes' => [
        'title' => 'Herstelcodes',

        'intro' => 'Wanneer tweestapsverificatie is ingeschakeld, wordt je tijdens de verificatie gevraagd om een token in te voeren. Je kunt deze token verkrijgen via de authenticatie-app op je telefoon, zoals Google Authenticator of wachtwoordbeheerders zoals 1Password.',

        'codes' => [
            'new' => 'Je nieuwe herstelcodes',
            'show' => 'Je herstelcodes tonen',

            'footnote' => 'Bewaar deze herstelcodes in een veilige wachtwoordbeheerder. Ze kunnen worden gebruikt om toegang tot je account te herstellen als je tweestapsverificatie-apparaat verloren gaat.',
        ],

        'regenerate' => [
            'action' => 'Nieuwe herstelcodes genereren',

            'confirm' => [
                'title' => 'Weet je het zeker?',
                'body_1' => 'Het opnieuw genereren van je herstelcodes maakt alle eerder gegenereerde herstelcodes onmiddellijk ongeldig.',
                'body_2' => 'Weet je zeker dat je dit wilt doen?',
            ],

            'success' => '',
        ],

        'show' => [
            'action' => 'Toon herstelcodes',
        ],
    ],

    'reset' => [
        'title' => 'Herstel tweestapsverificatie-instellingen',

        'me_intro_1' => 'Dit verwijdert alle tweestapsverificatiegegevens van je account.',
        'me_intro_2' => 'Je kan tweestapsverificatie opnieuw instellen in het bedieningspaneel.',

        'me_enforced_intro_1' => 'Dit verwijdert alle tweestapsverificatiegegevens van je account en logt je uit.',
        'me_enforced_intro_2' => 'De volgende keer dat je inlogt, moet je tweestapsverificatie opnieuw instellen voordat je toegang krijgt tot het bedieningspaneel.',

        'user_intro_1' => 'Dit verwijdert alle tweestapsverificatiegegevens van hun account.',
        'user_intro_2' => 'Ze kunnen tweestapsverificatie opnieuw instellen in hun profiel in het bedieningspaneel.',

        'user_enforced_intro_1' => 'Dit verwijdert alle tweestapsverificatiegegevens van hun account en logt hen uit.',
        'user_enforced_intro_2' => 'De volgende keer dat zij inloggen, moeten ze tweestapsverificatie opnieuw instellen voordat ze toegang krijgen tot het bedieningspaneel.',

        'confirm' => [
            'title' => 'Weet je het zeker?',

            'me_1' => 'Dit verwijdert alle tweestapsverificatiegegevens van je account.',
            'me_2' => 'Je kan tweestapsverificatie opnieuw instellen in je profiel in het bedieningspaneel.',
            'me_3' => 'Weet je zeker dat je dit wilt doen?',

            'me_enforced_1' => 'Dit verwijdert alle tweestapsverificatiegegevens van je account en logt je <strong>onmiddellijk</strong> uit.',
            'me_enforced_2' => 'Je moet tweestapsverificatie opnieuw instellen bij je volgende aanmelding.',
            'me_enforced_3' => 'Weet je zeker dat je dit wilt doen?',

            'user_1' => 'Dit verwijdert alle tweestapsverificatiegegevens van hun account.',
            'user_2' => 'Ze kunnen tweestapsverificatie opnieuw instellen in hun profiel in het bedieningspaneel.',
            'user_3' => 'Weet je zeker dat je dit wilt doen?',

            'user_enforced_1' => 'Dit verwijdert alle tweestapsverificatiegegevens van hun account en logt hen uit bij hun volgende bezoek.',
            'user_enforced_2' => 'Ze moeten tweestapsverificatie opnieuw instellen bij hun volgende aanmelding.',
            'user_enforced_3' => 'Weet je zeker dat je dit wilt doen?',
        ],

        'action' => 'Herstel tweestapsverificatie-instellingen',

        'success' => 'Tweestapsverificatiestatus succesvol hersteld.',
    ],

    'messages' => [
        'wrong_view' => 'Het tweestapsverificatieveldtype kan alleen worden gebruikt op de weergave "Gebruikers bewerken".',

        'not_setup_1' => 'Tweestapsverificatie is nog niet ingesteld.',
        'not_setup_2' => 'Je kunt de tweestapsverificatiegegevens beheren nadat de gebruiker het instelproces heeft voltooid.',

        'not_enabled' => 'Tweestapsverificatie is niet ingeschakeld voor deze site.',
    ],
];
