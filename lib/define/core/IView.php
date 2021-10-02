<?php
declare(strict_types=1);
namespace Define\Core;

if(DIRECT_ACCESS != true) die("Direct access is forbidden.");

/**
 * IVIEW
 *
 * View interface for View implementation.
 *
 * @category Define
 * @package Core
 * @author Nitesh Apte <me@niteshapte.com>
 * @copyright 2017 Nitesh Apte
 * @version 1.0.2
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
interface IView extends IDefine {
	
	/**
	 * Add objects for display in UI or to carry forward.
	 * 
	 * @param string $objectName
	 * @param mixed $objectValue
	 * @return void
	 */
	public function addObject(string $objectName, mixed $objectValue) : void;
	
	/**
	 * Renders an UI page
	 * 
	 * @param string $page Name of the UI page
	 * @return void
	 */
	public function render(string $page) : void;
	
	/**
	 * Redirects to a UI page
	 * 
	 * @param string $page Name of the UI page
	 * @return void
	 */
	public function redirect(string $page) : void;

    /**
     * Redirects to a page after a certain time period
     *
     * @param string $page Name of the UI page
     * @param int $time
     */
	public function redirectWithTime(string $page, int $time) : void;
	
	/**
	 * Add object to session to retrieve on UI
	 * 
	 * @param string $objectName
	 * @param mixed $objectValue
	 * @return void
	 */
	public function addObjectInSession(string $objectName, mixed $objectValue) : void;
	
	/**
	 * Removes an object for UI added to session
	 * 
	 * @param string $objectName
	 * @return void
	 */
	public function removeObjectInSession(string $objectName) : void;
}