# Statamic Two Factor

<!-- statamic:hide -->

![Statamic 4+](https://img.shields.io/badge/Statamic-4+-FF269E?style=for-the-badge&link=https://statamic.com)
[![Statamic Two Factor on Packagist](https://img.shields.io/packagist/v/mitydigital/statamic-two-factor?style=for-the-badge)](https://packagist.org/packages/mitydigital/statamic-two-factor/stats)

---

<!-- /statamic:hide -->

> A two factor addon for file-based users.

## Two Factor is a Commercial Addon.

You can use it for free while in development, but requires a license to use on a live site. Learn more or buy a license
on The Statamic Marketplace!

## How to use

After installation, add some env properties:

- `STATAMIC_TWO_FACTOR_ENABLED`, true or false. When true, will enforce two factor. When false, will disable (handy for
  your dev environment)
- `STATAMIC_TWO_FACTOR_ATTEMPTS_ALLOWED`, defaults to 5, optionally can increase. The number of attempts allowed before
  the user is locked out of their account
- `STATAMIC_TWO_FACTOR_VALIDITY`, defaults to 43200 (30 days), the number of minutes until a challenge should be made
  again. Set to null to disable repeating challenges for the session.

## User fields

The following fields are added for users, and should not be used by other handles for your user model:

- `two_factor_secret` the user's secret key
- `two_factor_recovery_codes` stores the valid recovery codes
- `two_factor_confirmed_at` when a successful code has been provided during setup
- `two_factor_completed` when recovery codes have been shown during setup
- `two_factor_locked` when a user gets locked due to exceeding failed attempts
- `two_factor` to store a summary for the field type in Statamic

## Database

If you use a database, there is a migration file to run. The fields noted above are included in the migration.

## Behaviour

This is an all-in approach. When enabled, anyone with CP access will be governed by two factor restrictions.

The "validity" period is used to re-challenge a logged in user every now and then. A POST or PATCH request (such as
saving an entry) will not trigger this - just to make it easier to not lose work - but on their next request (such as a
GET), they will be redirected to the challenge.

## Security

Security related issues should be emailed to [dev@mity.com.au](mailto:dev@mity.com.au) instead of logging an issue.

## Support

We love to share work like this, and help the community. However it does take time, effort and work.

The best thing you can do is [log an issue](../../issues).

Please try to be detailed when logging an issue, including a clear description of the problem, steps to reproduce the
issue, and any steps you may have tried or taken to overcome the issue too. This is an awesome first step to helping us
help you. So be awesome - it'll feel fantastic.

