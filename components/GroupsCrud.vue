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
    <div class="groups crud">
        <aui-crud v-bind="options">
            <template #list-title>Permission Groups</template>
            <template #form="{ form, error, overlay }">
                <div class="col-2">
                    <aui-input
                        label="Name"
                        :disabled="overlay"
                        :failure="error.name"
                        v-model="form.name"
                    />
                    <aui-input
                        label="Description"
                        :disabled="overlay"
                        :failure="error.description"
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
    </div>
</template>

<script>
export default {
    data() {
        return {
            options: {
                package: 'users',
                list_empty_text: 'No groups found',
                list_api_read: 'groups.list.read',
                form_api_read: 'group.read',
                form_api_create_setup: 'group.create.setup',
                form_api_create: 'group.create',
                form_api_update: 'group.update',
                form_api_delete: 'group.delete',
                form_storable_key: 'group_id',
                form_delete_text: 'Are you sure you want to delete the group?',
                fields: [
                    'group_id',
                    'name',
                    'description',
                    'assigned_permissions',
                    'unassigned_permissions'
                ],
                views: ['form']
            }
        }
    }
}
</script>

<style lang="sass">

.groups.crud
    max-width: 1000px

</style>