<?php
/**
 * Create a new user.
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
 * @noinspection PhpUnused
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
 * Create the instance.
 */
require_once realpath(__DIR__ . '/../../../scripts/create_instance.php');

/**
 * Get and check the length of CLI inputted data.
 *
 * @param string $prompt
 * @param string $key
 * @return mixed
 */
function getInputAndCheckLength(string $prompt, string $key)
{
    while (1) {
        $field = CLI::in($prompt);
        $property = "{$key}_min_length";
        if (mb_strlen($field) < Users::$cfg->$property) {
            CLI::err(ucfirst("$prompt too short"), false);
            continue;
        }
        if (mb_strlen($field) > User::COLUMN_LIMITS[$key]) {
            CLI::err(ucfirst("$prompt too long"), false);
            continue;
        }
        return $field;
    }
}

/**
 * Confirmation prompt.
 */
CLI::out('This script creates a root user. Root users bypass all permissions.');
CLI::out('You should only use this for making a account that you will use to make other accounts withing the GUI.');
if (!CLI::yn('I understand, proceed with creating user.')) {
    CLI::err('Cancelling...');
}

/**
 * Create the user.
 */
$user = new User();
$user->root = true;
while (1) {
    $user->username = getInputAndCheckLength('Username', 'username');
    while (1) {
        CLI::out('Password: ', false);
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
    CLI::out("Creating User: $user->username");
    if (CLI::yn('Confirm user?')) {
        $user->changePassword($user_password_1);
        break;
    }
}
try {
    DynamicSuite::$db->startTx();
    $user->create();
    (new Event([
        'package_id' => 'users',
        'created_by' => Session::$user_name,
        'affected' => $user->username,
        'type' => Users::EVENTS['USER_CREATE'],
        'event' => 'User created',
        'message' => 'CLI initiated'
    ]))->create();
    DynamicSuite::$db->endTx();
} catch (Exception | PDOException $exception) {
    CLI::err($exception->getMessage());
}
