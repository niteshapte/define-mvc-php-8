<?php
declare(strict_types=1);
namespace Define\Core;

if(DIRECT_ACCESS != true) die("Direct access is forbidden.");

/**
 * ROUTER
 *
 * Framework Router
 *
 * @category Define
 * @package Core
 * @author Nitesh Apte <me@niteshapte.com>
 * @copyright 2015 Nitesh Apte
 * @version 1.0.0
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
interface IRouter extends IDefine {

    /**
     * Start the processing. Either new or existing.
     *
     * @param array $options
     * @return void
     */
    public function init(array $options = array()) : void;

    /**
     * Setup up the path, controller, action and parameters.
     *
     * @return void
     */
    public function doSetup() : void;

    /**
     * Set the controller.
     *
     * @param string $controller Name of the controller
     * @return IRouter
     */
    public function setController(string $controller) : IRouter;

    /**
     * Set the action
     *
     * @param string $action Name of the action/method
     * @return IRouter
     */
    public function setAction(string $action) : IRouter;

    /**
     * Set the action's parameters.
     *
     * @param array
     * @return IRouter
     */
    public function setParams(array $params) : IRouter;

    /**
     * Call the controller with action and its parameters
     *
     * @return void
     */
    public function run() : void;

}