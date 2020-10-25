<?php
/**
 * Users package core class.
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
 * @noinspection PhpUnused
 */

namespace DynamicSuite\Pkg\Users;

/**
 * Class Users.
 *
 * @package DynamicSuite\Pkg\Users
 */
final class Users
{

    /**
     * Global configuration.
     *
     * @var Config
     */
    public static Config $cfg;

    /**
     * Event types.
     *
     * @var int[]
     */
    public const EVENTS = [
        'USER_LOGIN' => 100,
        'USER_CREATE' => 200,
        'USER_UPDATE' => 210,
        'USER_PASSWORD_RESET' => 215,
        'USER_DELETE' => 220,
        'GROUP_CREATE' => 300,
        'GROUP_UPDATE' => 310,
        'GROUP_DELETE' => 320
    ];

    /**
     * Initialize Users.
     *
     * @return void
     */
    public static function init(): void
    {
        self::$cfg = new Config('users');
    }

    /**
     * Render a Users component view.
     *
     * @param string $name
     * @return void
     */
    public static function renderComponentView(string $name): void
    {
        $view = '<div id="users-component-view" v-cloak>';
        $view .= "<users-$name></users-$name>";
        $view .= '</div>';
        echo $view;
    }

}