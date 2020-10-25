<?php
/**
 * Authenticate and login a user against local accounts database.
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
use DynamicSuite\Core\Session;
use DynamicSuite\Storable\Event;
use DynamicSuite\Storable\User;

/**
 * Generic response for invalid credentials
 */
$generic_response = new Response('CREDENTIAL', 'Invalid username or password', [
    'username' => 'Invalid username or password',
    'password' => 'Invalid username or password'
]);

/**
 * Look for the user
 */
if (!$user = User::readByUsername($_POST['username'])) {
    return $generic_response;
}

/**
 * User is inactive
 */
elseif ($user->inactive) {
    return new Response('LOGIN', 'Account Inactive');
}

/**
 * User is locked out from too many login attempts
 */
elseif (
    $user->login_attempts > Users::$cfg->login_max_attempts &&
    time() - strtotime($user->login_last_attempt) < Users::$cfg->login_lockout_timeout
) {
    $user->addLoginAttempt();
    $user->update();
    return new Response('LOGIN', Users::$cfg->login_lockout_message);
}

/**
 * Try to log in the user
 */
elseif ($user->login($_POST['password'])) {

    /**
     * Create a new session for the user and set some current values
     */
    Session::create($user->user_id);

    /**
     * Figure out where to redirect the user to since their login succeeded
     */
    if (isset($_POST['redirect'])) {
        $location = $_POST['redirect'];
    } else {
        $location = Users::$cfg->login_post_view;
    }

    /**
     * Log the event
     */
    (new Event([
        'package_id' => 'users',
        'created_by' => Session::$user_name,
        'affected' => $user->username,
        'type' => Users::EVENTS['USER_LOGIN'],
        'message' => 'User login'
    ]))->create();

    /**
     * OK response
     */
    return new Response('OK', 'Login successful', $location);

}

/**
 * Login failed due to bad credentials
 */
else {
    return $generic_response;
}