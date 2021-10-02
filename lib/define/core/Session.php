<?php
declare(strict_types=1);
namespace Define\Core;

use Define\Traits\SingletonTrait;

if(DIRECT_ACCESS != true) die("Direct access is forbidden.");

/**
 * SESSION
 *
 * Manages interactions with $ _SESSION variable
 *  
 * @category Define
 * @package Core
 * @author David Unay Santisteban <slavepens@gmail.com>, Nitesh Apte <me@niteshapte.com>
 * @copyright 2015
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
final class Session implements IDefine {
	
	use SingletonTrait;
	
	private bool $sessionStarted = FALSE;
	
	/**
	 * Start the session
	 * 
	 * @return void
	 */
	public function sessionStart() : void {
		// if no session exist, start the session
		if (session_id() == '') {
			session_start();
		}
	}

    /**
     * Gets the values â€‹â€‹stored in a session variable.
     *
     * @param ?string $index
     * @return array|string
     */
	public function getData(?string $index) : array|string {
		$session = array();
		foreach($_SESSION as $key => $value) {
			if(isset($value)) {
				$session[$key] = $value;
			}
		}
		if($index) {
			return $session[$index];
		}
		return $session;
	}
	
	/**
	 * Check if a value has been saved in session
	 * 
	 * @param string $index
	 * @return boolean
	 */
	public function hasData(string $index) : bool {
		return !empty($_SESSION[$index]);
	}

    /**
     * Set a session variable.
     *
     * @param string $index
     * @param mixed $value
     * @return void
     */
	public function setData(string $index, mixed $value) : void {
		if(!isset($index) && !isset($value)) {
            $_SESSION[$index] = $value;
		}
	}

    /**
     * Returns the session ID.
     *
     * @param ?string $id
     * @return string
     */
	public function sessionId(?string $id): string {
		return session_id($id);
	}
	
	/**
	 * Remove a value from session
	 * 
	 * @param string $index
	 * @return void
	 */
	public function removeData(string $index) : void {
		$_SESSION[$index] = null;
	}
	
	/**
	 * Regenerate session
	 * 
	 * @return bool|string
     */
	public function regenerate() : bool|string	{
		session_regenerate_id(TRUE);
		return session_id();
	}
	 
	/**
	 * Returns the session state.
	 * 
	 * @return int
     */
	public function sessionStatus() : int {
		return session_status();
	}
	 
	/**
	 * Purge custom session variables.
	 * 
	 * @return boolean
	 */
	public function sessionPurge() : bool {
		return session_unset();
	}
	 
	/**
	 * Destroy the FULL session.
	 * 
	 * @return boolean
	 */
	public function sessionDestroy() : bool {
		return session_destroy();
	}
}