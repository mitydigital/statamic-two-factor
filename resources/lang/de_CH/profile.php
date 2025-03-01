<?php

return [

    'enable' => [
        'title' => 'Zwei-Faktor-Authentifizierung aktivieren',

        'intro' => 'Mit dem Einrichten der Zwei-Faktor-Authentifizierung wirst du während der Anmeldung nach einem Authentifizierungscode gefragt. Dieser Code wird von TOTP-Apps wie Google Authenticator, Bitwarden oder FreeOTP+ generiert.',

        'enable' => 'Zwei-Faktor-Authentifizierung einrichten',
    ],

    'locked' => [
        'title' => 'Konto ist gesperrt',

        'intro' => 'Das Konto dieses Benutzers wurde aufgrund zu vieler gescheiterten Zwei-Faktor-Versuche gesperrt.',

        'unlock' => 'Konto entsperren',

        'confirm_title' => 'Konto entsperren?',
        'confirm_1' => 'Das Entsperren dieses Kontos ermöglicht dem Benutzer sich erneut anzumelden.',
        'confirm_2' => 'Möglicherweise ist es sinnvoll, das Passwort des Benutzers zurückzusetzen oder sogar die Zwei-Faktor-Authentifizierung als Vorsichtsmassnahme zurückzusetzen.',
        'confirm_3' => 'Möchtest du dies wirklich tun?',

        'success' => 'Konto wurde entsperrt.',
    ],

    'recovery_codes' => [
        'title' => 'Wiederherstellungscodes',

        'intro' => 'Mit dem Einrichten der Zwei-Faktor-Authentifizierung wirst du während der Anmeldung nach einem Authentifizierungscode gefragt. Dieser Code wird von TOTP-Apps wie Google Authenticator, Bitwarden oder FreeOTP+ generiert.',

        'codes' => [
            'new' => 'Deine neuen Wiederherstellungscodes',
            'show' => 'Deine Wiederherstellungscodes',

            'footnote' => 'Speichere diese Wiederherstellungscodes an einem sicheren Ort ab. Diese können für die Wiederherstellung deines Kontos im Falle eines Verlustes deines Geräts verwendet werden.',
        ],

        'regenerate' => [
            'action' => 'Neue Wiederherstellungscodes erstellen',

            'confirm' => [
                'title' => 'Bist du dir sicher?',
                'body_1' => 'Die Erstellung neuer Wiederherstellungscodes macht alle vorherigen Codes sofort ungültig.',
                'body_2' => 'Möchtest du dies wirklich tun?',
            ],

            'success' => '',
        ],

        'show' => [
            'action' => 'Wiederherstellungscodes anzeigen',
        ],
    ],

    'reset' => [
        'title' => 'Zwei-Faktor-Authentifizierung zurücksetzen',

        'me_intro_1' => 'Dies entfernt Angaben der Zwei-Faktor-Authentifizierung von deinem Konto.',
        'me_intro_2' => 'Du kannst die Zwei-Faktor-Authentifizierung erneut auf deinem Profil im Kontrollzentrum aufsetzen.',

        'me_enforced_intro_1' => 'This will remove all two factor authentication details from your account, and log you out.',
        'me_enforced_intro_2' => 'The next time you log in, you will need to set up two factor authentication again before you can access the Control Panel.',

        'user_intro_1' => 'Dies entfernt alle Angaben der Zwei-Faktor-Authentifizierung von ihrem Konto.',
        'user_intro_2' => 'Sie kann die Zwei-Faktor-Authentifizierung erneut auf ihrem Profil im Kontrollzentrum aufsetzen.',

        'user_enforced_intro_1' => 'This will remove all two factor authentication details from their account, and log you them out.',
        'user_enforced_intro_2' => 'The next time they log in, they will need to set up two factor authentication again before they can access the Control Panel.',

        'confirm' => [
            'title' => 'Are you sure?',

            'me_1' => 'Dies entfernt Angaben der Zwei-Faktor-Authentifizierung von deinem Konto.',
            'me_2' => 'Du kannst die Zwei-Faktor-Authentifizierung erneut auf deinem Profil im Kontrollzentrum aufsetzen.',
            'me_3' => 'Möchtest du dies wirklich tun?',

            'me_enforced_1' => 'Dies entfernt die Zwei-Faktor-Authentifizierung und meldet dich <strong>direkt</strong> ab.',
            'me_enforced_2' => 'Bei der nächsten Anmeldung wirst du zum erneuten Aufsetzen aufgefordert.',
            'me_enforced_3' => 'Möchtest du dies wirklich tun?',

            'user_1' => 'Dies entfernt alle Angaben der Zwei-Faktor-Authentifizierung von ihrem Konto.',
            'user_2' => 'Sie kann die Zwei-Faktor-Authentifizierung erneut auf ihrem Profil im Kontrollzentrum aufsetzen.',
            'user_3' => 'Möchtest du dies wirklich tun?',

            'user_enforced_1' => 'Dies entfernt die Zwei-Faktor-Authentifizierung von den ausgewählten Konten und meldet diese <strong>direkt</strong> ab',
            'user_enforced_2' => 'Bei der nächsten Anmeldung werden diese zum erneuten Aufsetzen aufgefordert.',
            'user_enforced_3' => 'Möchtest du dies wirklich tun?',
        ],

        'action' => 'Zwei-Faktor-Authentifizierung zurücksetzen',

        'success' => 'Zwei-Faktor-Authentifizierung wurde erfolgreich zurückgesetzt.',
    ],

    'messages' => [
        'wrong_view' => 'Das Zwei-Faktor-Feld kann lediglich bei der Ansicht «Benutzer bearbeiten» verwendet werden.',

        'not_setup_1' => 'Zwei-Faktor-Authentifizierung wurde nicht aufgesetzt.',
        'not_setup_2' => 'Du kannst diese nach dem Aufsetzen durch den Benutzer verwalten.',

        'not_enabled' => 'Zwei-Faktor-Authentifizierung ist für diese Seite nicht aktiviert.',
    ],
];
