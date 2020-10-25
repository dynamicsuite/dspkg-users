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
        <aui-crud v-bind="options">
            <template #list-title>Local Users</template>
            <template #form="{ form, error, overlay }">
                <aui-input
                    label="Username"
                    :failure="error.username"
                    :disabled="overlay"
                    v-model="form.username"
                />
                <aui-checkbox :checked="form.inactive" :disabled="overlay" v-model="form.inactive">
                    Inactive
                </aui-checkbox>
                <h2>Password</h2>
                <div class="col-2">
                    <aui-input
                        label="Password"
                        type="password"
                        :failure="error.password_1"
                        :disabled="overlay"
                        v-model="form.password_1"
                    />
                    <aui-input
                        label="Confirm Password"
                        type="password"
                        :failure="error.password_2"
                        :disabled="overlay"
                        v-model="form.password_2"
                    />
                </div>
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
                fields: [
                    'user_id',
                    'username',
                    'password_1',
                    'password_2',
                    'root',
                    'inactive',
                    'inactive_on',
                    'login_last_attempt',
                    'login_last_success',
                    'login_last_ip',
                    'assigned_groups',
                    'unassigned_groups'
                ],
                secure_fields: [
                    'password_1',
                    'password_2'
                ],
                views: ['form']
            }
        }
    }
}
</script>

<style lang="sass">

.users.crud
    max-width: 1000px

</style>