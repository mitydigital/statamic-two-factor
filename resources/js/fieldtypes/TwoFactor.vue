<template>

    <div>
        <template v-if="!meta.enabled">
            <div class="text-sm">
                <p>{{ __('statamic-two-factor::profile.messages.not_enabled') }}</p>
            </div>
        </template>
        <template v-else-if="meta.is_me && meta.is_user_edit && !setup">

            <two-factor-enable
                :route="meta.routes.setup"/>

        </template>
        <template v-else-if="!meta.is_me && meta.is_user_edit && !setup">
            <div class="text-sm">
                <p class="font-medium mb-2">{{ __('statamic-two-factor::profile.messages.not_setup_1') }}</p>
                <p>{{ __('statamic-two-factor::profile.messages.not_setup_2') }}</p>
            </div>
        </template>
        <template v-else-if="meta.is_user_edit">

            <two-factor-locked
                v-if="locked"
                :route="meta.routes.locked"
                @update="updateState"/>

            <two-factor-recovery-codes
                v-if="meta.is_me"
                :routes="meta.routes.recovery_codes"/>

            <two-factor-reset
                :route="meta.routes.reset"
                :enforced="meta.is_enforced"
                :language-user="languageUser"
                @update="updateState"/>

        </template>
        <template v-else>

            <div class="text-sm">
                <p class="text-red-500 font-medium">{{ __('statamic-two-factor::profile.messages.wrong_view') }}</p>
            </div>

        </template>
    </div>

</template>

<script>

import TwoFactorEnable from './partials/Enable.vue';
import TwoFactorLocked from './partials/Locked.vue';
import TwoFactorRecoveryCodes from './partials/RecoveryCodes.vue';
import TwoFactorReset from './partials/Reset.vue';

export default {

    mixins: [Fieldtype],

    components: {
        TwoFactorEnable,
        TwoFactorLocked,
        TwoFactorRecoveryCodes,
        TwoFactorReset
    },

    computed: {
        languageUser() {
            return (this.meta.is_me ? 'me' : 'user') + (this.meta.is_enforced ? '_enforced' : '');
        }
    },

    data: function () {
        return {
            locked: false,
            setup: false
        }
    },

    methods: {
        updateState(field, status) {
            // update the status
            this.$data[field] = status;
        }
    },

    mounted() {
        this.locked = this.meta.is_locked;
        this.setup = this.meta.is_setup;
    },
};
</script>
