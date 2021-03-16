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
    <aui-crud v-bind="options" :form.sync="form" :feedback.sync="feedback" :calling.sync="calling" class="users-view">
        <template #list-title>Permission Groups</template>
        <template #form="{ overlay }">
            <div class="col-2">
                <aui-input
                    label="Name"
                    :disabled="overlay"
                    :failure="feedback.name"
                    v-model="form.name"
                />
                <aui-input
                    label="Description"
                    :disabled="overlay"
                    :failure="feedback.description"
                    v-model="form.description"
                />
            </div>
            <aui-select-assignment
                :assigned.sync="form.assigned_permissions"
                :unassigned.sync="form.unassigned_permissions"
                :disabled="overlay"
            />
        </template>
    </aui-crud>
</template>

<script>
export default {
    data() {
        return {
            options: {
                package: 'users',
                list_api_read: 'groups.list.read',
                list_empty_text: 'No groups found',
                form_storable_key: 'group_id',
                form_api_read: 'group.read',
                form_api_create_setup: 'group.create.setup',
                form_api_create: 'group.create',
                form_api_update: 'group.update',
                form_api_delete: 'group.delete',
                form_delete_text: 'Are you sure you want to delete the group?',
                views: {
                    form: 'Form'
                }
            },
            form: {
                group_id: null,
                name: null,
                description: null,
                assigned_permissions: null,
                unassigned_permissions: null
            },
            feedback: {
                name: null,
                description: null
            },
            calling: false
        }
    }
}
</script>