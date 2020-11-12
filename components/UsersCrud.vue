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
    <div class="users crud">
        <aui-crud v-bind="options" :form.sync="form" :feedback.sync="feedback" :calling.sync="calling">
            <template #list-title>Local Users</template>
            <template #form="{ overlay }">
                <aui-input
                    label="Username"
                    :failure="feedback.username"
                    :disabled="overlay"
                    v-model="form.username"
                />
                <aui-checkbox
                    label="Inactive"
                    :checked="form.inactive"
                    :disabled="overlay"
                    :failure="feedback.inactive"
                    v-model="form.inactive"
                />
                <h2>Password</h2>
                <div class="col-2">
                    <aui-input
                        label="Password"
                        type="password"
                        :failure="feedback.password_1"
                        :disabled="overlay"
                        v-model="form.password_1"
                    />
                    <aui-input
                        label="Confirm Password"
                        type="password"
                        :failure="feedback.password_2"
                        :disabled="overlay"
                        v-model="form.password_2"
                    />
                </div>
                <aui-checkbox
                    label="Force change password at next login"
                    :checked="form.password_expired"
                    :disabled="overlay"
                    v-model="form.password_expired"
                />
                <h2>Groups</h2>
                <aui-select-assignment
                    :assigned.sync="form.assigned_groups"
                    :unassigned.sync="form.unassigned_groups"
                    :disabled="overlay"
                />
            </template>
        </aui-crud>
    </div>
</template>

<script>
export default {
    data() {
        return {
            options: {
                package: 'users',
                list_empty_text: 'No users found',
                list_api_read: 'users.list.read',
                form_api_read: 'user.read',
                form_api_create_setup: 'user.create.setup',
                form_api_create: 'user.create',
                form_api_update: 'user.update',
                form_api_delete: 'user.delete',
                form_storable_key: 'user_id',
                form_delete_text: 'Are you sure you want to delete the user?',
                secure_fields: [
                    'password_1',
                    'password_2'
                ],
                views: ['form']
            },
            form: {
                user_id: null,
                username: null,
                password_1: null,
                password_2: null,
                password_expired: null,
                root: null,
                inactive: null,
                inactive_by: null,
                inactive_on: null,
                login_last_attempt: null,
                login_last_success: null,
                login_last_ip: null,
                assigned_groups: null,
                unassigned_groups: null
            },
            feedback: {
                username: null,
                password_1: null,
                password_2: null,
                inactive: null,
                assigned_groups: null,
                unassigned_groups: null
            },
            calling: false
        }
    }
}
</script>

<style lang="sass">

.users.crud
    max-width: 1000px

    .body > .aui.input:first-of-type
        margin-bottom: 1rem

</style>