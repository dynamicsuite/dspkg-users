<?php
/**
 * Users view: login.
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
use DynamicSuite\Core\Session;
use DynamicSuite\Core\View;

/**
 * Destroy the old session
 */
Session::destroy();

/**
 * Set the page data for the component
 */
View::setPageData([
    'application_header' => Users::$cfg->login_application_header,
    'form_header' => Users::$cfg->login_form_header,
    'footer' => Users::$cfg->login_footer,
    'register_href' => Users::$cfg->login_register_href,
    'register_text' => Users::$cfg->login_register_text,
    'help_href' => Users::$cfg->login_help_href,
    'help_text' => Users::$cfg->login_help_text
]);

/**
 * Render the component view.
 */
Users::renderComponentView('login');