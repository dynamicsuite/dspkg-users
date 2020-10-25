<?php
/**
 * Read a permission group.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation version 3.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software Foundation,
 * Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301  USA
 *
 * @package Users
 * @author Grant Martin <commgdog@gmail.com>
 * @copyright 2020 Dynamic Suite Team
 * @noinspection PhpUnhandledExceptionInspection
 */

namespace DynamicSuite\Pkg\Users;
use DynamicSuite\API\Response;
use DynamicSuite\Database\Query;

/**
 * Read the group
 */
$group = (new Query())
    ->select([
        'group_id',
        'name',
        'description'
    ])
    ->from('ds_groups')
    ->where('group_id', '=', $_POST['id'])
    ->execute(true);

/**
 * Group not found
 */
if (!$group) {
    return new Response('NOT_FOUND', 'Group not found');
}

/**
 * Read permissions
 */
$group['assigned_permissions'] = [];
$group['unassigned_permissions'] = [];
$assigned = (new Query())
    ->select(['ds_permissions.permission_id', 'ds_permissions.description'])
    ->from('ds_groups_permissions')
    ->join('ds_permissions')
    ->on('ds_permissions.permission_id', '=', 'ds_groups_permissions.permission_id')
    ->where('ds_groups_permissions.group_id', '=', $group['group_id'])
    ->execute();
$unassigned = (new Query())
    ->select(['permission_id', 'description'])
    ->from('ds_permissions')
    ->execute();
foreach ($unassigned as $value) {
    $group['unassigned_permissions'][$value['permission_id']] = $value['description'];
}
foreach ($assigned as $value) {
    unset($group['unassigned_permissions'][$value['permission_id']]);
    $group['assigned_permissions'][$value['permission_id']] = $value['description'];
}

/**
 * OK response
 */
return new Response('OK', 'Success', $group);