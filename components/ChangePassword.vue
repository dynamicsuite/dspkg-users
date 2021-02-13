<!--
Users Package
Copyright (C) 2021 Dynamic Suite Team

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation version 3.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software Foundation,
Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301  USA
-->

<template>
    <div class="dspkg-users change-password">
        <div v-if="!error.server" class="aui aui-container primary">
            <h3>Update Account Password</h3>
            <aui-input
                type="password"
                label="Password"
                autocomplete="chrome-off"
                :failure="error.password"
                v-model="form.password_1"
            />
            <aui-input
                type="password"
                label="Confirm Password"
                autocomplete="chrome-off"
                :failure="error.password"
                v-model="form.password_2"
            />
            <aui-button
                text="Update Password"
                :loading="state.calling"
                loading_text="Updating..."
                @click="changePassword"
            />
        </div>
        <aui-notice
            v-else
            type="failure"
            icon="fas fa-exclamation-triangle"
            text="A server error occurred"
            subtext="Please refresh the page"
        />
    </div>
</template>

<script>
export default {
    data() {
        return {
            state: {
                calling: false
            },
            form: {
                password_1: null,
                password_2: null
            },
            error: {
                server: false,
                password: null
            },
            page_data: {
                password_expired: false
            }
        }
    },
    methods: {

        /**
         * Change the user's password.
         *
         * @returns {undefined}
         */
        changePassword () {
            this.state.calling = true;
            this.error.password = null;
            let get = new URLSearchParams(window.location.search.substr(1));
            let redirect = get.get('ref');
            if (redirect === null || redirect === '') redirect = '/login';
            DynamicSuite.call('users', 'change.password', this.form, response => {
                switch (response.status) {
                    case 'OK':
                        window.location = redirect;
                        break;
                    case 'BAD_PASSWORD':
                    case 'NO_CHANGE_REQUIRED':
                        this.error.password = response.message;
                        this.state.calling = false;
                        break;
                    default:
                        this.error.server = true;
                }
            });
        }
    }

}
</script>

<style lang="sass">

/* Change password container */
.dspkg-users.change-password
    width: 100vw
    height: 100vh
    display: flex
    align-items: center
    justify-content: center
    flex-direction: column

    h1
        font-style: italic

    h1, h3
        text-align: center

    .btn, .aui.input
        margin-top: 1rem

    .btn
        width: 100%

</style>