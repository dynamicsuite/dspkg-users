<?php
/**
 * Delete a permission group.
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
use DynamicSuite\Storable\Event;
use DynamicSuite\Storable\Group;

/**
 * Check if the group exists
 */
if (!$group = Group::readById($_POST['group_id'])) {
    return new Response('NOT_FOUND', 'Group not found');
}

/**
 * Start a new database transaction
 */
DynamicSuite::$db->startTx();

/**
 * Delete the group
 */
$group->delete();

/**
 * Log the event
 */
(new Event([
    'package_id' => 'users',
    'created_by' => Session::$user_name,
    'affected' => $group->name,
    'type' => Users::EVENTS['GROUP_DELETE'],
    'message' => 'Group deleted'
]))->create();

/**
 * End the database transaction
 */
DynamicSuite::$db->endTx();

/**
 * OK response
 */
return new Response('OK', 'Success');