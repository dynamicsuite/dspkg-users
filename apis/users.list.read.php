<?php
/**
 * Read all of the local user accounts.
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
use DynamicSuite\Pkg\Aui\CrudRead;
use DynamicSuite\Pkg\Time\Time;

/**
 * Setup the list read.
 */
$list = (new Query())
    ->select([
        'user_id AS id',
        'username AS title',
        'login_last_success AS subtext'
    ])
    ->from('ds_users');

/**
 * Set up the read.
 */
$crud = (new CrudRead($list))
    ->searchColumns(['username'])
    ->execute();

/**
 * Format subtext
 */
foreach ($crud['list'] as $key => $value) {
    $crud['list'][$key]['subtext'] = 'Last Login: ' . Time::timestamp($value['subtext']);
}

/**
 * Return the component data.
 */
return new Response('OK', 'Success', $crud);