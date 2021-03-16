<?php
/**
 * Change the current (own) password.
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
 * Make sure the user is valid.
 */
if (!$user = User::readById(Session::$user_id)) {
    return new Response('BAD_USER', 'Invalid user account');
}

/**
 * Password empty.
 */
if (empty($_POST['password_1'])) {
    return new Response('BAD_PASSWORD', 'Password empty');
}

/**
 * Do not match.
 */
if ($_POST['password_1'] !== $_POST['password_2']) {
    return new Response('BAD_PASSWORD', 'Passwords do not match');
}

/**
 * Too short.
 */
if (mb_strlen($_POST['password_1']) < Users::$cfg->password_min_length) {
    return new Response('BAD_PASSWORD', 'Password too short');
}

/**
 * Change the password.
 */
$user->changePassword($_POST['password_1']);
$user->password_expired = false;

/**
 * Begin a new transaction.
 */
DynamicSuite::$db->startTx();

/**
 * Update the user.
 */
$user->update();

/**
 * Log the event.
 */
(new Event([
    'package_id' => 'users',
    'created_by' => Session::$user_name,
    'affected' => $user->username,
    'type' => Users::EVENTS['USER_LOGIN'],
    'event' => 'Password changed by user'
]))->create();


/**
 * Complete the transaction.
 */
DynamicSuite::$db->endTx();

/**
 * OK response.
 */
return new Response('OK', 'Success');