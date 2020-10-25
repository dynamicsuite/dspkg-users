<?php
/**
 * Reset a users password.
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
use DynamicSuite\Core\DynamicSuite;
use DynamicSuite\Core\Session;
use DynamicSuite\Storable\Event;
use DynamicSuite\Storable\User;
use DynamicSuite\Util\CLI;
use Exception;
use PDOException;

/**
 * Create the instance
 */
require_once realpath(__DIR__ . '/../../../scripts/create_instance.php');

$user = User::readByUsername(CLI::in('Username to reset'));
if (!$user) {
    CLI::err('User not found');
}

while (1) {
    CLI::out('New Password: ', false);
    exec('stty -echo');
    $user_password_1 = trim(fgets(STDIN));
    exec('stty echo');
    CLI::out('');
    CLI::out('Confirm Password: ', false);
    exec('stty -echo');
    $user_password_2 = trim(fgets(STDIN));
    exec('stty echo');
    CLI::out('');
    if ($user_password_1 !== $user_password_2) {
        CLI::err('Passwords do not match', false);
    }
    if (mb_strlen($user_password_1) < Users::$cfg->password_min_length) {
        CLI::err('Password too short', false);
    }
    break;
}
$user->changePassword($user_password_1);
try {
    DynamicSuite::$db->startTx();
    $user->update();
    (new Event([
        'package_id' => 'users',
        'created_by' => Session::$user_name,
        'affected' => $user->username,
        'type' => Users::EVENTS['USER_PASSWORD_RESET'],
        'message' => 'User password reset'
    ]))->create();
    DynamicSuite::$db->endTx();
    CLI::out('Password changed');
} catch (Exception | PDOException $exception) {
    CLI::err($exception->getMessage());
}