<?php

return [

    'enable' => [
        'title' => "Abilitare l'autenticazione a due fattori",

        'intro' => "Quando l'autenticazione a due fattori è attiva, verrà richiesto un token. Il token può essere recuperato dall'applicazione di autenticazione del telefono, come Google Authenticator o tramite gestori di password come 1Password.",

        'enable' => "Impostare l'autenticazione a due fattori",
    ],

    'locked' => [
        'title' => "L'account è stato bloccato",

        'intro' => "L'account di questo utente è stato bloccato a causa di ripetuti tentativi di accesso non validi.",

        'unlock' => 'Account ripristinato',

        'confirm_title' => "Ripristinare l'account?",
        'confirm_1' => "Ripristinando questo account, l'utente potrà accedere nuovamente al Pannello di controllo.",
        'confirm_2' => "Per precauzione potrebbe essere utile modificare la password dell'utente e riconfigurare l'autenticazione a due fattori.",
        'confirm_3' => 'Vuoi continuare?',

        'success' => "L'account è stato ripristinato.",
    ],

    'recovery_codes' => [
        'title' => 'Codici di emergenza',

        'intro' => "Quando l'autenticazione a due fattori è attiva, verrà richiesto un token. Il token può essere recuperato dall'applicazione di autenticazione del telefono, come Google Authenticator o tramite gestori di password come 1Password.",

        'codes' => [
            'new' => 'I tuoi nuovi codici di emergenza',
            'show' => 'I tuoi codici di emergenza',

            'footnote' => 'Conserva questi codici in un luogo sicuro. Se non riesci ad accedere al tuo account con la normale verifica in due passaggi, puoi utilizzare uno dei seguenti codici di emergenza.',
        ],

        'regenerate' => [
            'action' => 'Nuovi codici di emergenza',

            'confirm' => [
                'title' => 'Vuoi continuare?',
                'body_1' => 'Tutti i codici di emergenza creati in precedenza verranno annullati e non sarà più possibile utilizzarli per accedere. Saranno generati nuovi codici di emergenza.',
                'body_2' => 'Vuoi davvero continuare?',
            ],

            'success' => '',
        ],

        'show' => [
            'action' => 'Visualizza codici di emergenza',
        ],
    ],

    'reset' => [
        'title' => "Reimposta l'autenticazione a due fattori",

        'me_intro_1' => "Disattiva l'autenticazione a due fattori dal tuo account.",
        'me_intro_2' => "Puoi configurare nuovamente l'autenticazione a due fattori dal tuo profilo nel Pannello di Controllo.",

        'me_enforced_intro_1' => "Disattiva l'autenticazione a due fattori dal tuo account. Verrà effettuato il logout",
        'me_enforced_intro_2' => "Al prossimo accesso, dovrai configurare l'autenticazione a due fattori.",

        'user_intro_1' => "Rimuoverai l'autenticazione a due fattori dai loro account.",
        'user_intro_2' => 'Potranno abilitarla nuovamente dal loro profilo nel Pannello di Controllo.',

        'user_enforced_intro_1' => "Rimuoverai l'autenticazione a due fattori dal loro account e verranno disconnessi.",
        'user_enforced_intro_2' => 'Al prossimo accesso, dovranno configurarla prima di poter utilizzare il Pannello di Controllo.',

        'confirm' => [
            'title' => 'Vuoi continuare?',

            'me_1' => "Rimuoverai l'autenticazione a due fattori dal tuo account.",
            'me_2' => 'Puoi configurarla nuovamente dal tuo profilo nel Pannello di Controllo.',
            'me_3' => 'Sei sicuro di voler procedere?',

            'me_enforced_1' => "Rimuoverai l'autenticazione a due fattori dal tuo account e verrai disconnesso <strong>immediatamente</strong>.",
            'me_enforced_2' => 'Dovrai configurarla al prossimo accesso.',
            'me_enforced_3' => 'Sei sicuro di voler procedere?',

            'user_1' => "Rimuoverai l'autenticazione a due fattori dal loro account.",
            'user_2' => 'Potranno configurarla nuovamente dal loro profilo nel Pannello di Controllo.',
            'user_3' => 'Sei sicuro di voler procedere?',

            'user_enforced_1' => "Rimuoverai l'autenticazione a due fattori dal loro account e verranno disconnessi al prossimo accesso.",
            'user_enforced_2' => 'Dovranno configurarla al prossimo accesso.',
            'user_enforced_3' => 'Sei sicuro di voler procedere?',
        ],

        'action' => "Reimposta l'autenticazione a due fattori",

        'success' => "L'autenticazione a due fattori è stata ripristinata.",
    ],

    'messages' => [
        'wrong_view' => "Il campo per l'autenticazione a due fattori può essere utilizzato solo nella vista 'Modifica utenti'.",

        'not_setup_1' => "L'autenticazione a due fattori non è ancora stata attivata.",
        'not_setup_2' => "È possibile gestire i dettagli dell'autenticazione a due fattori dopo che l'utente ha completato il processo di configurazione.",

        'not_enabled' => "L'autenticazione a due fattori non è attiva.",
    ],
];
