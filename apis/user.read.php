<?php
/**
 * Read a local user account.
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
use DynamicSuite\Storable\Group;

/**
 * Read the user
 */
$user = (new Query())
    ->select([
        'user_id',
        'username',
        'password_expired',
        'root',
        'inactive',
        'inactive_by',
        'inactive_on',
        'login_last_attempt',
        'login_last_success',
        'login_last_ip'
    ])
    ->from('ds_users')
    ->where('user_id', '=', $_POST['id'])
    ->execute(true);

/**
 * User not found
 */
if (!$user) {
    return new Response('NOT_FOUND', 'User not found');
}

/**
 * Read the groups
 */
$groups = Group::readForComponent(null, $user['user_id']);
$user['assigned_groups'] = $groups['assigned'];
$user['unassigned_groups'] = $groups['unassigned'];

/**
 * OK response
 */
return new Response('OK', 'Success', $user);
