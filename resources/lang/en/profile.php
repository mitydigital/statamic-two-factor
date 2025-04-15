<?php

return [

    'enable' => [
        'title' => 'Enable two factor authentication',

        'intro' => 'When two factor authentication is enabled, you will be prompted for a token during authentication. You may retrieve this token from your phone\'s authenticator application, such as Google Authenticator or password managers like 1Password.',

        'enable' => 'Set up two factor authentication',
    ],

    'locked' => [
        'title' => 'Account is locked',

        'intro' => 'This user\'s account has been locked due to too many failed two factor challenge attempts.',

        'unlock' => 'Unlock account',

        'confirm_title' => 'Unlock account?',
        'confirm_1' => 'Unlocking this account will allow the user to attempt to log in again.',
        'confirm_2' => 'It may also be useful to have them reset their password, and even reset their two factor authentication setup as a precaution.',
        'confirm_3' => 'Are you sure you want to do this?',

        'success' => 'Account has been unlocked.',
    ],

    'recovery_codes' => [
        'title' => 'Recovery codes',

        'intro' => 'When two factor authentication is enabled, you will be prompted for a token during authentication. You may retrieve this token from your phone\'s authenticator application, such as Google Authenticator or password managers like 1Password.',

        'codes' => [
            'new' => 'Your new recovery codes',
            'show' => 'Your recovery codes',

            'footnote' => 'Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two factor authentication device is lost.',
        ],

        'regenerate' => [
            'action' => 'Create new recovery codes',

            'confirm' => [
                'title' => 'Are you sure?',
                'body_1' => 'Regenerating your recovery codes will invalidate all previously created recovery codes with immediate effect.',
                'body_2' => 'Are you sure you want to do this?',
            ],

            'success' => '',
        ],

        'show' => [
            'action' => 'Show recovery codes',
        ],
    ],

    'reset' => [
        'title' => 'Reset two factor setup',

        'me_intro_1' => 'This will remove all two factor authentication details from your account.',
        'me_intro_2' => 'You can set up two factor authentication again from your Profile in the Control Panel.',

        'me_enforced_intro_1' => 'This will remove all two factor authentication details from your account, and log you out.',
        'me_enforced_intro_2' => 'The next time you log in, you will need to set up two factor authentication again before you can access the Control Panel.',

        'user_intro_1' => 'This will remove all two factor authentication details from their account.',
        'user_intro_2' => 'They will be able to enable two factor authentication from their Profile in the Control Panel.',

        'user_enforced_intro_1' => 'This will remove all two factor authentication details from their account, and log them out.',
        'user_enforced_intro_2' => 'The next time they log in, they will need to set up two factor authentication again before they can access the Control Panel.',

        'confirm' => [
            'title' => 'Are you sure?',

            'me_1' => 'This will remove all two factor authentication details from your account.',
            'me_2' => 'You can set up two factor authentication from your Profile in the Control Panel.',
            'me_3' => 'Are you really sure you want to do this?',

            'me_enforced_1' => 'This will remove all two factor authentication details from your account, and log you out <strong>immediately</strong>.',
            'me_enforced_2' => 'You will need to set up two factor authentication on your next log in.',
            'me_enforced_3' => 'Are you really sure you want to do this?',

            'user_1' => 'This will remove all two factor authentication details from their account.',
            'user_2' => 'They can set up two factor authentication from their Profile in the Control Panel.',
            'user_3' => 'Are you really sure you want to do this?',

            'user_enforced_1' => 'This will remove all two factor authentication details from their account, and log them out on their next visit.',
            'user_enforced_2' => 'They will need to set up two factor authentication on their next log in.',
            'user_enforced_3' => 'Are you really sure you want to do this?',
        ],

        'action' => 'Reset two factor setup',

        'success' => 'Successfully reset two factor status.',
    ],

    'messages' => [
        'wrong_view' => 'The Two Factor fieldtype can only be used on the "Edit users" view.',

        'not_setup_1' => 'Two Factor Authentication has not been set up yet.',
        'not_setup_2' => 'You can manage two factor authentication details after the user has completed the setup process.',

        'not_enabled' => 'Two Factor Authentication is not enabled for this site.',
    ],
];
