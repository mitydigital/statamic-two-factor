# Two Factor for Statamic

<!-- statamic:hide -->

![Statamic 5+](https://img.shields.io/badge/Statamic-5+-FF269E?style=for-the-badge&link=https://statamic.com)
[![Statamic Two Factor on Packagist](https://img.shields.io/packagist/v/mitydigital/statamic-two-factor?style=for-the-badge)](https://packagist.org/packages/mitydigital/statamic-two-factor/stats)

---

<!-- /statamic:hide -->

> A two factor authentication (2FA) addon for Statamic.

Two Factor for Statamic is an addon for Statamic that enables users of the Control Panel (CP) to utilise two factor
authentication using a one-time password (such as with Google Authenticator or password manager apps like 1Password).

This addon can enforce two factor authentication to:

- all CP users (the default), or
- users by Role (the `enforced_roles` config property)

If you have two Roles - Admin and Author - and you enforce two factor for Admin, any Author users can optionally opt-in
to Two Factor by visiting their profile.

## Features

This addon enables two factor authentication for users of the Control Panel. When enabled, users will be prompted to set
up two factor before they can continue. The addon also creates emergency recovery codes for each user's account.

This addon:

- can be enabled or disabled per environment (such as disabling on your local dev environment)
- can be enabled for all users (default) or by Roles, with non-enforced Roles optionally able to opt-in
  locks user accounts after a number of failed two factor challenge attempts
- can re-challenge users after a period of time (default to 30 days, or can be disabled)
- adds a fieldtype for user and profile editing

Users are able to:

- show or re-generate their emergency recovery codes
- remove two factor authentication (which will log them out, and require set up on their next login)

Admin can:

- unlock user accounts
- remove two factor setup details from any user

These admin behaviours are connected to Statamic's "edit" permission for Users.

## Two Factor for Statamic is a Commercial Addon.

You can use it for free while in development, but requires a license to use on a live site.

Learn more or buy a license on the [Statamic Marketplace](https://statamic.com/addons/mity-digital/two-factor)!

## Documentation

See the [documentation](https://docs.mity.com.au/two-factor) for detailed installation, configuration and usage
instructions.

## Testing

```bash
composer test
```

## Security

Security related issues should be emailed to [dev@mity.com.au](mailto:dev@mity.com.au) instead of logging an issue.

## Support

We love to share work like this, and help the community. However it does take time, effort and work.

The best thing you can do is [log an issue](../../issues).

Please try to be detailed when logging an issue, including a clear description of the problem, steps to reproduce the
issue, and any steps you may have tried or taken to overcome the issue too. This is an awesome first step to helping us
help you. So be awesome - it'll feel fantastic.

## Credits

- [Marty Friedel](https://github.com/martyf)
- [Fabio Widmer](https://github.com/FabioWidmer) and [Marco Rieser](https://github.com/marcorieser) for Swiss German
  translations
- [Joshua van der Poll](https://github.com/joshuavanderpoll) and [Richard Verbruggen](https://github.com/vannut) for
  Dutch translations
- [Håvard Grimelid](https://github.com/hgrimelid) for Norwegian Bokmål translations
- [Encodia](https://github.com/encodiaweb) for Italian translations
