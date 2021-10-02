<?php
declare(strict_types=1);
namespace Define\Core;

use Define\Exceptions\FrameworkException;
use JetBrains\PhpStorm\NoReturn;

if(DIRECT_ACCESS != true) die("Direct access is forbidden.");

/**
 * VIEW
 *
 * View class for rendering user interface. This should not be final to keep it extendable.
 * Feel free to extend it and implement your own functionalities.
 *  
 * @category Define
 * @package Core
 * @author Nitesh Apte <me@niteshapte.com>
 * @copyright 2017 Nitesh Apte
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
class View implements IView {
	
	private array $vars = array();
		
	/**
	 * {@inheritDoc}
	 * @see \Define\Core\IView::addObject()
	 */
	public function addObject(string $objectName, mixed $objectValue) : void {
		$this->vars[$objectName] = $objectValue;
	}

	/**
	 * {@inheritDoc}
	 * @see \Define\Core\IView::render()
	 */
	public function render(string $page) : void {
        sizeof($this->vars) ? extract($this->vars) : NULL;
		include sprintf("%s%s.php", VIEW_PATH, $page);
	}

	/**
	 * {@inheritDoc}
	 * @see \Define\Core\IView::redirect()
	 */
	#[NoReturn] public function redirect(string $page) : void {
		sizeof($this->vars) ?? extract($this->vars);
		header('location: '.$page);
		exit;
	}

	/**
	 * {@inheritDoc}
	 * @see \Define\Core\IView::redirectWithTime()
	 */
	#[NoReturn] public function redirectWithTime(string $page, int $time) : void {
		sizeof($this->vars) ?? extract($this->vars);
		header("refresh:" . $time . "; url=" . $page);
		exit;
	}

    /**
     * {@inheritDoc}
     * @throws FrameworkException
     * @see \Define\Core\IView::addObjectInSession()
     */
	public function addObjectInSession(string $objectName, mixed $objectValue) : void {
		$container = RegisterObject::getInstance();
		$session = $container->offsetGet('SESSION');
		$session->setData($objectName, $objectValue);
	}

    /**
     * {@inheritDoc}
     * @throws FrameworkException
     * @see \Define\Core\IView::removeObjectInSession()
     */
	public function removeObjectInSession(string $objectName) : void {
		$container = RegisterObject::getInstance();
		$session = $container->offsetGet('SESSION');
		$session->removeData($objectName);
	}
}