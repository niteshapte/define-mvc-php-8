<?php
declare(strict_types = 1);

use Define\Core\Framework;
use Define\Core\RegisterObject;
use Define\Core\Router;
use Define\Core\Session;
use Define\DBDrivers\DatabaseBean;
use Define\Exceptions\FrameworkException;
use Define\Utilities\Logger;

/**
 * BOOTSTRAP
 *
 * Initialization script. Front Controller
 *
 * @category Define
 * @package /
 * @author Nitesh Apte <me@niteshapte.com>
 * @copyright 2015-2021 Nitesh Apte
 * @version 2.0.0
 * @since 1.0.0
 * @license https://www.gnu.org/licenses/gpl.txt GNU General Public License v3
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

ini_set('display_errors', "1");

ob_start();
session_start();
session_regenerate_id();


# Access to all the pages will be possible only through bootstrap. After all this is a front controller based MVC Framework.
const DIRECT_ACCESS = true;

# Settings for framework - Start #
# Set the input paths.
# Framework path
set_include_path(get_include_path().PATH_SEPARATOR."lib/define/core");
set_include_path(get_include_path().PATH_SEPARATOR."lib/define/dbdrivers");
set_include_path(get_include_path().PATH_SEPARATOR."lib/define/exceptions");
set_include_path(get_include_path().PATH_SEPARATOR."lib/define/traits");
set_include_path(get_include_path().PATH_SEPARATOR."lib/define/utilities");

include_once 'configuration/define.php';

# Settings for framework - End #

# Settings for application - Start #
# Set the input paths.
# Application path
set_include_path(get_include_path().PATH_SEPARATOR."application/controller");
set_include_path(get_include_path().PATH_SEPARATOR."application/service/");
set_include_path(get_include_path().PATH_SEPARATOR."application/repository/");
set_include_path(get_include_path().PATH_SEPARATOR."application/dto");
set_include_path(get_include_path().PATH_SEPARATOR."application/exceptions");


/**
 * Autoload method for dynamically loading classes.
 *
 * @param string $object Name of Class
 * @return void
 */
function autoload(string $object) {
    $split = explode("\\", $object);
    $className = end($split);
    require_once("$className.php");
}

spl_autoload_register("autoload");

try {
    $container = RegisterObject::getInstance();
    $container->offsetSet('FRAMEWORK', Framework::getInstance());
    $container->offsetSet('SESSION', Session::getInstance());
    $container->offsetSet('LOGGER', Logger::getInstance());
    $container->offsetSet('MARIADB', new DatabaseBean("MARIADB", "host", "username", "pass", "schema", "3306", "db"));
    $session = $container->offsetGet('SESSION');
    $default = array();
    $container->offsetGet('FRAMEWORK')->init(Router::getInstance(), $default);
} catch (FrameworkException $e) {
    echo $e;
}
