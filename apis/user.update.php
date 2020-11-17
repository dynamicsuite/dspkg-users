<?php
/**
 * Update an existing local user account.
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
use DynamicSuite\Storable\User;

/**
 * Check if the user exists
 */
if (!$user = User::readById($_POST['user_id'])) {
    return new Response('NOT_FOUND', 'User not found');
}

/**
 * Validate for length errors in the given data
 */
$errors = (new CrudPostValidation())
    ->limits(User::COLUMN_LIMITS)
    ->minimums([
        'username' => Users::$cfg->username_min_length
    ])
    ->validate();

/**
 * Password match failure
 */
if ($_POST['password_1'] && $_POST['password_1'] !== $_POST['password_2']) {
    $errors['password_1'] = 'Passwords do not match';
    $errors['password_2'] = 'Passwords do not match';
} elseif ($_POST['password_1'] && mb_strlen($_POST['password_1']) < Users::$cfg->password_min_length) {
    $errors['password_1'] = 'Password too short';
    $errors['password_2'] = 'Password too short';
}

/**
 * Own account check
 */
if ($user->user_id === Session::$user_id && $_POST['inactive']) {
    $errors['inactive'] = 'Cannot deactivate your own account';
}

/**
 * Make sure the username is not in use
 */
$usage_check = User::readByUsername($_POST['username']);
if ($usage_check && $usage_check->user_id !== $user->user_id) {
    $errors['username'] = 'Username in use';
}

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
 * Update the user
 */
$user->username = $_POST['username'];
$user->setInactive((bool) $_POST['inactive']);
if ($_POST['password_1']) {
    $user->changePassword($_POST['password_1']);
}
$user->password_expired = (bool) $_POST['password_expired'];
$user->update();

/**
 * Add the groups
 */
$group_insert = [];
foreach ($_POST['assigned_groups'] as $group_id => $name) {
    $group_insert[] = [
        'user_id' => $user->user_id,
        'group_id' => $group_id
    ];
}
(new Query())
    ->delete()
    ->from('ds_users_groups')
    ->where('user_id', '=', $user->user_id)
    ->execute();
if ($group_insert) {
    (new Query())
        ->insert($group_insert)
        ->into('ds_users_groups')
        ->execute();
}

/**
 * Log the event
 */
(new Event([
    'package_id' => 'users',
    'created_by' => Session::$user_name,
    'affected' => $user->username,
    'type' => Users::EVENTS['USER_UPDATE'],
    'message' => 'User update'
]))->create();

/**
 * End the database transaction
 */
DynamicSuite::$db->endTx();

/**
 * OK response
 */
return new Response('OK', 'Success');