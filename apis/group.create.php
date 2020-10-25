<?php
/**
 * Create a new permission group.
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
use DynamicSuite\Core\DynamicSuite;
use DynamicSuite\Core\Session;
use DynamicSuite\Database\Query;
use DynamicSuite\Pkg\Aui\CrudPostValidation;
use DynamicSuite\Storable\Event;
use DynamicSuite\Storable\Group;

/**
 * Validate for length errors in the given data
 */
$errors = (new CrudPostValidation())
    ->limits(Group::COLUMN_LIMITS)
    ->minimums([
        'name' => Users::$cfg->group_name_min_length,
        'description' => Users::$cfg->group_description_min_length
    ])
    ->validate();

/**
 * Input validation failed
 */
if ($errors) {
    return new Response('INPUT_ERROR', 'Input validation error', $errors);
}

/**
 * Start a new database transaction
 */
DynamicSuite::$db->startTx();

/**
 * Create the group
 */
$group = new Group();
$group->name = $_POST['name'];
$group->description = $_POST['description'];
$group->create();

/**
 * Add the permissions
 */
$permission_insert = [];
foreach ($_POST['assigned_permissions'] as $permission_id => $name) {
    $permission_insert[] = [
        'group_id' => $group->group_id,
        'permission_id' => $permission_id
    ];
}
(new Query())
    ->insert($permission_insert)
    ->into('ds_groups_permissions')
    ->execute();

/**
 * Log the event
 */
(new Event([
    'package_id' => 'users',
    'created_by' => Session::$user_name,
    'affected' => $group->name,
    'type' => Users::EVENTS['GROUP_CREATE'],
    'message' => 'Group created'
]))->create();

/**
 * End the database transaction
 */
DynamicSuite::$db->endTx();

/**
 * OK response
 */
return new Response('OK', 'Success', $group->group_id);