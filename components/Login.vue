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
    <div class="dspkg-users login">
        <div v-if="!error.server">
            <h1 v-if="application_header">{{application_header}}</h1>
            <div class="login-content aui aui-container primary">
                <header>{{form_header}}</header>
                <aui-input
                    id="username"
                    placeholder="Username"
                    leading_cap="<i class='fas fa-user'></i>"
                    :failure="error.form.username"
                    v-model="form.username"
                    @keydown="handleKeydown"
                />
                <aui-input
                    type="password"
                    placeholder="Password"
                    leading_cap="<i class='fas fa-key'></i>"
                    :failure="error.form.password"
                    v-model="form.password"
                    @keydown="handleKeydown"
                />
                <aui-button
                    text="Login"
                    :loading="state.calling"
                    loading_text="Logging in..."
                    @click="login"
                />
                <a v-if="register_href" :href="register_href">{{register_text}}</a>
                <a v-if="help_href" :href="help_href">{{help_text}}</a>
                <aui-alert
                    :text="error.login"
                    :show="error.login"
                    type="failure"
                />
            </div>
            <footer v-if="footer">{{footer}}</footer>
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
            application_header: 'Login Application Header',
            form_header: 'Login Form Header',
            footer: '© My Application',
            register_href: null,
            register_text: 'Register a new account',
            help_href: null,
            help_text: 'I forgot my password',
            username_input: null,
            form: {
                username: null,
                password: null
            },
            state: {
                calling: false
            },
            error: {
                server: false,
                login: null,
                form: {
                    username: null,
                    password: null
                }
            }
        }
    },
    methods: {

        /**
         * Handle keydown events for hitting enter to login.
         *
         * @returns {undefined}
         */
        handleKeydown(event) {
            if (event.key === 'Enter') {
                this.login();
            }
        },

        /**
         * Attempt to login the user.
         *
         * @returns {undefined}
         */
        login() {
            this.error.login = null;
            this.error.form.username = null;
            this.error.form.password = null;
            this.state.calling = true;
            let get = new URLSearchParams(window.location.search.substr(1));
            let redirect = get.get('ref');
            if (typeof redirect === 'undefined' || redirect === '') {
                redirect = null;
            }
            DynamicSuite.call('users', 'login', Object.assign({}, this.form, { redirect: redirect }), response => {
                this.form.password = null;
                switch (response.status) {
                    case 'OK':
                        window.location = response.data;
                        break;
                    case 'CREDENTIAL':
                        this.error.credential = true;
                        this.$set(this.error, 'form', Object.assign(this.error.form, response.data));
                        this.state.calling = false;
                        this.username_input.focus();
                        this.username_input.select();
                        break;
                    case 'LOGIN':
                        this.error.login = response.message;
                        this.state.calling = false;
                        break;
                    default:
                        this.error.server = true;
                }
            });
        }

    },
    mounted() {
        const page_data = DynamicSuite.getPageData();
        for (const key in page_data) {
            if (page_data.hasOwnProperty(key) && this.hasOwnProperty(key)) {
                this[key] = page_data[key];
            }
        }
        this.username_input = document.getElementById('username');
        if (this.username_input) {
            this.username_input = this.username_input.getElementsByTagName('input')[0];
            this.username_input.focus();
        }
    }
}
</script>

<style lang="sass">

/* Import the core DS colors */
@import "../../../client/css/colors"

/* Global CRUD width */
.users-view
    max-width: 1008px

/* Login container */
.dspkg-users.login
    display: flex
    flex-direction: column
    justify-content: center
    align-items: center
    text-align: center
    width: 100%
    height: 100vh

    /* Application header */
    h1
        font-size: 1.5rem

    /* Login form container */
    .login-content
        width: 18rem

        /* Organization name header */
        header
            width: calc(100% + 2rem)
            font-weight: bold
            background: $primary
            color: white
            padding: 1rem 0
            margin: -1rem 0 1rem -1rem
            border-top-left-radius: 3px
            border-top-right-radius: 3px

        /* Space out form */
        .aui.input
            margin-bottom: 1rem

            /* Pad caps and inputs */
            .leading-cap, input
                padding: 0.75rem

            /* Square up caps */
            .leading-cap i
                width: 1rem
                height: 1rem

        /* Stretch the login button to the full form width */
        .btn
            width: 100%
            padding: 0.75rem

        /* Failed login notice*/
        .alert
            margin-top: 1rem
            display: flex
            justify-content: center

    /* The login footer copyright */
    footer
        margin-top: -0.3rem
        color: darken($text-muted, 20%)
        font-size: 0.8rem

    /* Mobile overrides */
    @media (max-width: 500px)

        /* Hide SOM header */
        h1
            display: none

        /* Flex parent container */
        & > div
            display: flex
            flex-direction: column
            width: 100%
            height: 100%

            /* Remove header radius */
            header
                border-radius: 0

            /* Form container overrides */
            .login-content
                width: calc(100% - 2rem)
                flex-grow: 1
                border: none
                box-shadow: none
                margin-bottom: 0
                border-radius: 0

        /* Copyright footer overrides */
        footer
            margin-top: auto
            padding-bottom: 1rem
            background: whitesmoke

</style>