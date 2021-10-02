<?php
declare(strict_types=1);
namespace Define\Core;

use Define\Exceptions\FrameworkException;
use Define\Traits\SingletonTrait;
use ReflectionClass;
use ReflectionException;
use function call_user_func_array;
use function file_exists;
use function mb_convert_case;
use function mb_strtolower;
use function str_replace;
use const MB_CASE_TITLE;

if(DIRECT_ACCESS != true) die("Direct access is forbidden.");

/**
 * ROUTER
 *
 * Framework Router
 *
 * @category Define
 * @package Core
 * @author Nitesh Apte <me@niteshapte.com>
 * @copyright 2021 Nitesh Apte
 * @version 3.0
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
final class Router implements IRouter {

    use SingletonTrait;

    const DEFAULT_CONTROLLER 	= DEFAULT_CONTROLLER;
    const DEFAULT_ACTION     	= DEFAULT_ACTION;
    private string $controller  = self::DEFAULT_CONTROLLER;
    private string $action      = self::DEFAULT_ACTION;
    private array $params       = array();

    /**
     * @inheritDoc
     * @throws ReflectionException
     */
    public function init(array $options = array()) : void {
        empty($options) ? $this->doSetup() : $this->get($options);
    }

    /**
     * Set controller, action and parameters
     *
     * @param array $options Parameters
     * @return void
     * @throws ReflectionException
     */
    private function get(array $options = array()) : void {
        (isset($options["controller"]) && $options['controller'] != '') ? $this->setController($options["controller"]) : CONTROLLER_NAMESPACE . $this->controller;
        isset($options["action"]) ? $this->setAction($options["action"]) : $this->action;
        isset($options["params"]) ? $this->setParams($options["params"]) : $this->params;
    }

    /**
     * @inheritDoc
     * @throws ReflectionException
     */
    public function doSetup() : void {
        $path = parse_url(filter_var($_SERVER["REQUEST_URI"], FILTER_SANITIZE_URL), PHP_URL_PATH);

        substr($path, -1) != NEED_SLASH ? $this->setController(ERROR_CONTROLLER) : $path = trim($path, '/');

        $path = preg_replace('/[^a-zA-Z0-9]\//', "", $path);

        $attr = explode(SEPARATOR, $path, 3);

        !empty($attr[0]) ? $this->setController($attr[0]) : $this->setController($this->controller);
        !empty($attr[1]) ? $this->setAction($attr[1]) : $this->setAction($this->action);
        !empty($attr[2]) ? $this->setParams(explode("-", $attr[2])) : $this->setParams($this->params);
    }

    /**
     * @inheritDoc
     */
    public function setController(string $controller) : IRouter {
        //For the name with 2 or more words. For example - /nitesh-apte/someaction/. Controller name will be NiteshApteController
        $controller = str_replace("-", "", mb_convert_case(mb_strtolower($controller), MB_CASE_TITLE, "UTF-8"));
        $this->controller = CONTROLLER_NAMESPACE . CONTROLLER_PREFIX . $controller . CONTROLLER_SUFFIX;
        $file = CONTROLLER_PATH . CONTROLLER_PREFIX . $controller . CONTROLLER_SUFFIX.'.php';

        if (!file_exists($file)) {
            $this->controller = CONTROLLER_NAMESPACE . CONTROLLER_PREFIX . ERROR_CONTROLLER . CONTROLLER_SUFFIX;
        }
        return $this;
    }

    /**
     * @inheritDoc
     * @throws ReflectionException
     */
    public function setAction(string $action) : IRouter {
        $action = str_replace("-", "", mb_convert_case(mb_strtolower($action), MB_CASE_TITLE, "UTF-8"));
        $reflector = new ReflectionClass($this->controller);
        $this->action = lcfirst($action . ACTION_SUFFIX);
        if (!$reflector->hasMethod($this->action)) {
            $this->controller = CONTROLLER_NAMESPACE . CONTROLLER_PREFIX . ERROR_CONTROLLER . CONTROLLER_SUFFIX;
            $this->action = self::DEFAULT_ACTION . ACTION_SUFFIX;
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setParams(array $params) : IRouter {
        $this->params = $params;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function run() : void {
        try {
            $controller = new $this->controller();
            if(!$controller instanceof BaseController) {
                throw new FrameworkException("Controller $this->controller is not of type Define\Core\BaseController.", 1003);
            }
            call_user_func_array(array($controller, $this->action), $this->params);
        } catch (FrameworkException $e) {
            echo $e;
        }
    }
}