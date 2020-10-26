<!--
Users Package
Copyright (C) 2020 Dynamic Suite Team

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
    <div class="users change-password">
        <div v-if="!error.server" class="ds-container">
            <h1 v-if="page_data.password_expired">Password Expired</h1>
            <h3>Update Account Password</h3>
            <aui-input type="password" label="Password" :failure="error.password" v-model="form.password_1" />
            <aui-input type="password" label="Confirm Password" :failure="error.password" v-model="form.password_2" />
            <aui-button @click="changePassword" :loading="state.calling" loading_text="Updating...">Update</aui-button>
        </div>
        <aui-notice v-else type="failure" icon="fas fa-exclamation-triangle">Server Error</aui-notice>
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
    },
    mounted() {
        this.page_data = DynamicSuite.getPageData();
    }
}
</script>

<style lang="sass">

/* Change password container */
.users.change-password
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