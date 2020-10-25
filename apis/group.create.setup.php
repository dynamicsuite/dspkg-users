<?php
/**
 * Get the available permissions to pre-fill the create form select assignment.
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
 * Get permissions
 */
$permissions = [
    'assigned_permissions' => [],
    'unassigned_permissions' => []
];
$unassigned = (new Query())
    ->select(['permission_id', 'description'])
    ->from('ds_permissions')
    ->execute();
foreach ($unassigned as $value) {
    $permissions['unassigned_permissions'][$value['permission_id']] = $value['description'];
}

/**
 * OK response
 */
return new Response('OK', 'Success', $permissions);