<?php
declare(strict_types=1);
namespace Define\Core;

if(DIRECT_ACCESS != true) die("Direct access is forbidden.");

use Define\Exceptions\FrameworkException;
use Define\Traits\SingletonTrait;
use Define\Utilities\ErrorExceptionHandler;
use Define\Utilities\Localization;
use ReflectionException;
use function explode;
use function ini_get;
use function strtolower;
use function trim;

/**
 * FRAMEWORK
 *
 * Starting point of application. Initializes the application.
 *  
 * @category Define
 * @package Core
 * @author Nitesh Apte <me@niteshapte.com>
 * @copyright 2017 Nitesh Apte
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
final class Framework implements IDefine {
	
	// Singleton class
	use SingletonTrait;

    /**
     * Initialize the necessary stuffs for running the application
     *
     * @return void
     * @throws FrameworkException
     */
	private function __construct() {
		ErrorExceptionHandler::getInstance();
		$this->removeMagicQuotes();
		$this->unRegisterGlobals();
		$this->loadLocalization();
	}

    /**
     * Initialize working of Router
     *
     * @param Router $router
     * @param array $option
     * @return void
     * @throws ReflectionException
     */
	public function init(Router $router, array $option = array()) : void {
		$router->init($option);
		$router->run();
	}

    /**
     * Remove magic quotes
     */
	private function removeMagicQuotes() : void {
		$_GET = $this->stripSlashesDeep($_GET);
        $_POST = $this->stripSlashesDeep($_POST);
		$_COOKIE = $this->stripSlashesDeep($_COOKIE);
	}

    /**
     * Unregister GLOBAL variables
     */
	private function unRegisterGlobals() : void {
		if (ini_get('register_globals')) {
			$array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
			foreach ($array as $value) {
				foreach ($GLOBALS[$value] as $key => $var) {
					if ($var === $GLOBALS[$key]) {
						unset($GLOBALS[$key]);
					}
				}
			}
		}
	}

    /**
     * @param array|string $value
     * @return array|string
     */
	private function stripSlashesDeep(array|string $value) : array|string {
        return is_array($value) ? array_map(array($this,'stripSlashesDeep'), $value) : stripslashes($value);
	}

    /**
     * Loads language from $_SERVER['HTTP_ACCEPT_LANGUAGE']
     *
     * @throws FrameworkException
     */
    private function loadLocalization() {
		$temp = explode('-', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
		$locObj = Localization::getInstance();
		$language = include $locObj->loadLanguage(trim(strtolower($temp[0])));
		//$language = include $locObj->loadLanguage(trim(strtolower('en')));

		RegisterObject::getInstance()->offsetSet('LOCALIZATION', $language);
	}
}