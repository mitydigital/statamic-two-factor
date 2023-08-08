import TwoFactorField from './fieldtypes/TwoFactor.vue';
import TwoFactorFieldIndex from './fieldtypes/TwoFactorIndex.vue';

Statamic.booting(() => {
    Statamic.$components.register('two_factor-fieldtype', TwoFactorField);
    Statamic.$components.register('two_factor-fieldtype-index', TwoFactorFieldIndex);
});
