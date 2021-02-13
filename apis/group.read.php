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
 * @copyright 2021 Dynamic Suite Team
 * @noinspection PhpUnhandledExceptionInspection
 */

namespace DynamicSuite\Pkg\Users;
use DynamicSuite\API\Response;
use DynamicSuite\Database\Query;
use DynamicSuite\Storable\Permission;

/**
 * Read the group.
 */
$group = (new Query())
    ->select([
        'group_id',
        'name',
        'description'
    ])
    ->from('ds_groups')
    ->where('group_id', '=', $_POST['group_id'])
    ->execute(true);

/**
 * Group not found.
 */
if (!$group) {
    return new Response('NOT_FOUND', 'Group not found');
}

/**
 * Read permissions.
 */
$permissions = Permission::readForComponent(null, $group['group_id']);
$group['assigned_permissions'] = $permissions['assigned'];
$group['unassigned_permissions'] = $permissions['unassigned'];

/**
 * OK response.
 */
return new Response('OK', 'Success', $group);