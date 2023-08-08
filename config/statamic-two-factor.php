<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Is two factor enforced?
    |--------------------------------------------------------------------------
    |
    | When enabled, two factor authentication is required by all users of the
    | Statamic CP. This will direct them to a setup screen on their next
    | page visit, or the next time they sign in.
    |
    */

    'enabled' => env('STATAMIC_TWO_FACTOR_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | Blueprint field
    |--------------------------------------------------------------------------
    |
    | The name of the blueprint field handle for the status storage of the
    | user's two factor authentication status (setup and locked).
    |
    */

    'blueprint' => 'two_factor',

    /*
    |--------------------------------------------------------------------------
    | Number of incorrect two factor code attempts
    |--------------------------------------------------------------------------
    |
    | Only a specific number of incorrect attempts are allowed. This helps by
    | locking an account to prevent a bot from brute forcing their way in.
    | This count is incremented on each incorrect code or recovery code
    | attempt. When a challenge is successfully completed, the value
    | resets to zero.
    |
    | Default: 5
    |
    */

    'attempts' => env('STATAMIC_TWO_FACTOR_ATTEMPTS_ALLOWED', 5),

    /*
    |--------------------------------------------------------------------------
    | Two factor code validity
    |--------------------------------------------------------------------------
    |
    | The code validity will keep tabs on the last time the user was asked to
    | complete a two factor challenge. When this period expires, they will
    | be asked to complete another challenge. Stored as the number of
    | minutes.
    |
    | Default: 43200 minutes (30 days)
    |
    | Set to null to disable this feature.
    |
    */

    'validity' => env('STATAMIC_TWO_FACTOR_VALIDITY', 43200),

];
