<?php
/**
 * Delete a local user account.
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
use DynamicSuite\Core\DynamicSuite;
use DynamicSuite\Core\Session;
use DynamicSuite\Storable\Event;
use DynamicSuite\Storable\User;

/**
 * Check if the user exists.
 */
if (!$user = User::readById($_POST['user_id'])) {
    return new Response('NOT_FOUND', 'User not found');
}

/**
 * Own account check.
 */
if ($user->user_id === Session::$user_id) {
    return new Response('DELETE_PROTECT', 'Cannot delete your own account');
}

/**
 * Start a new database transaction.
 */
DynamicSuite::$db->startTx();

/**
 * delete the user.
 */
$user->delete();

/**
 * Log the event.
 */
(new Event([
    'package_id' => 'users',
    'created_by' => Session::$user_name,
    'affected' => $user->username,
    'type' => Users::EVENTS['USER_DELETE'],
    'event' => 'User deleted'
]))->create();

/**
 * End the database transaction.
 */
DynamicSuite::$db->endTx();

/**
 * OK response.
 */
return new Response('OK', 'Success');