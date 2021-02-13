<?php
/**
 * Users runtime configuration.
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
use DynamicSuite\Core\GlobalConfig;

/**
 * Class Config.
 *
 * @package DynamicSuite\Pkg\Users
 * @property int $login_max_attempts
 * @property int $login_lockout_timeout
 * @property string $login_lockout_message
 * @property string $login_post_view
 * @property int $username_min_length
 * @property int $password_min_length
 * @property string $login_application_header
 * @property string $login_form_header
 * @property string $login_footer
 * @property string|null $login_register_href
 * @property string $login_register_text
 * @property string|null $login_help_href
 * @property string $login_help_text
 * @property int $group_name_min_length
 * @property int $group_description_min_length
 */
final class Config extends GlobalConfig
{

    /**
     * Set max login attempts.
     *
     * @var int
     */
    protected int $login_max_attempts = 5;

    /**
     * The number of seconds a user is locked out for when they have too many login attempts.
     *
     * @var int
     */
    protected int $login_lockout_timeout = 300;

    /**
     * Message a user is prompted with when they get locked out.
     *
     * @var string
     */
    protected string $login_lockout_message = 'Too many login attempts<br />try back later';

    /**
     * The view the user is redirected to after a successful login.
     *
     * @var string
     */
    protected string $login_post_view = '/dynamicsuite/about';

    /**
     * Minimum length a username can be.
     *
     * @var int
     */
    protected int $username_min_length = 1;

    /**
     * Minimum length a password can be.
     *
     * @var int
     */
    protected int $password_min_length = 4;

    /**
     * Login screen: Application header text.
     *
     * @var string
     */
    protected string $login_application_header = 'Login Application Header';

    /**
     * Login screen: Form header text.
     *
     * @var string
     */
    protected string $login_form_header = 'Login Form Header';

    /**
     * Login screen: Form footer text.
     *
     * @var string
     */
    protected string $login_footer = '© My Application';

    /**
     * Login screen: Link to account registration.
     *
     * If this is NULL, the registration link will be hidden.
     *
     * @var string|null
     */
    protected ?string $login_register_href = null;

    /**
     * Login screen: Text on the register account link (if present).
     *
     * @var string
     */
    protected string $login_register_text = 'Register a new account';

    /**
     * Login screen: Link to account help.
     *
     * If this is NULL, the help link will be hidden.
     *
     * @var string|null
     */
    protected ?string $login_help_href = null;

    /**
     * Login screen: Text on the account help link (if present).
     *
     * @var string
     */
    protected string $login_help_text = 'I forgot my password';

    /**
     * Minimum length a permission group name can be.
     *
     * @var int
     */
    protected int $group_name_min_length = 1;

    /**
     * Minimum length a permission group description can be.
     *
     * @var int
     */
    protected int $group_description_min_length = 1;

    /**
     * Config constructor.
     *
     * @param string $package_id
     * @return void
     */
    public function __construct(string $package_id)
    {
        parent::__construct($package_id);
    }

}