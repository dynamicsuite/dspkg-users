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
    <div class="users login">
        <header v-if="application_header" v-html="application_header" />
        <div class="container">
            <div class="shadow">
                <header>{{form_header}}</header>
                <form onsubmit="return false;">
                    <aui-input
                        id="username"
                        placeholder="Username"
                        leading_cap="<i class='fas fa-user'></i>"
                        :disable_autofill="false"
                        :failure="error.form.username"
                        v-model="form.username"
                    />
                    <aui-input
                        placeholder="Password"
                        type="password"
                        leading_cap="<i class='fas fa-key'></i>"
                        :disable_autofill="false"
                        :failure="error.form.password"
                        v-model="form.password"
                    />
                    <aui-button :loading="state.calling" loading_text="Logging in..." @click="login">Login</aui-button>
                    <aui-alert type="failure" :visible="error.login" v-html="error.login" />
                    <a v-if="register_href" :href="register_href">{{register_text}}</a>
                    <a v-if="help_href" :href="help_href">{{help_text}}</a>
                </form>
            </div>
            <footer v-if="footer">{{footer}}</footer>
        </div>
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
         * Attempt to login the user
         *
         * @return void
         */
        login () {
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
        this.username_input.focus();
    }
}
</script>

<style lang="sass">

/* Import the core DS colors */
@import "../../../client/css/colors"

/* Media query mixin for mobile view breakpoint */
@mixin on-mobile-view
    @media (max-width: 500px)
        @content

/* Media query mixin for ipad view breakpoint */
@mixin on-ipad-view
    @media (max-width: 768px)
        @content

/* Login container */
.users.login
    display: flex
    flex-direction: column
    justify-content: center
    align-items: center
    text-align: center
    width: 100vw
    height: 100vh
    background: #d2d6de

    /* Clear darker background on mobile */
    @include on-mobile-view
        background: whitesmoke

    /* Application header */
    & > header
        margin-bottom: 2rem
        font-size: 1.5rem
        font-weight: bold

        @include on-mobile-view
            display: none

    /* Login container */
    .container
        width: 22rem

        /* Full height form on mobile */
        @include on-mobile-view
            display: flex
            align-items: stretch
            flex-direction: column
            width: 100%
            height: 100%

        /* Shadow backdrop container */
        .shadow
            box-shadow: 0 0 12px -3px rgba(0, 0, 0, 0.47)
            border-radius: 4px

            /* Mobile shadow resets */
            @include on-mobile-view
                box-shadow: none
                border-radius: 0

        /* Form header */
        header
            font-weight: bold
            background: $primary
            color: white
            padding: 1rem 1.5rem
            border-radius: 4px 4px 0 0
            margin: 0

            @include on-mobile-view
                border-radius: 0

        /* The actual login form container */
        form
            background: whitesmoke
            border-radius: 0 0 4px 4px
            border-top: none
            display: flex
            flex-direction: column
            padding: 1.5rem

            /* Input margins */
            .aui.input
                margin-bottom: 1.5rem

                /* Increase input padding*/
                .leading-cap, input
                    padding: 0.75rem

                /* Fixed icons */
                .leading-cap i
                    width: 1rem
                    height: 1rem

            /* Login button padding */
            .btn
                padding: 0.75rem

                /* Bottom padding when links are present */
                &:not(:last-child)
                    margin-bottom: 1.5rem

            /* Login alert */
            .alert
                display: flex
                justify-content: center
                margin: 0

            /* Action links*/
            a
                text-decoration: none
                color: $primary
                font-size: 0.9rem
                padding: 0 1rem

                &:hover
                    text-decoration: underline

                &:first-of-type:not(:last-of-type)
                    margin-bottom: 0.25rem

        /* Login footer/copyright */
        footer
            margin-top: 0.7rem
            color: #777
            font-size: 0.8rem
            padding: 0 2rem

            /* Move to bottom on mobile */
            @include on-mobile-view
                color: $text-muted
                margin: auto 0 0.5rem 0

</style>