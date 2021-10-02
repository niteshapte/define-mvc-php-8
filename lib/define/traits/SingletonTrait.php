<?php
declare(strict_types=1);
namespace Define\Traits;

use Define\Exceptions\CloneNotSupportedException;
use Define\Exceptions\NotSerializableException;

if(DIRECT_ACCESS != true) die("Direct access is forbidden.");

/**
 * SINGLETON TRAIT
 *
 * Singleton trait for the singleton classes.
 *
 * @category Define
 * @package Traits
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
trait SingletonTrait {

    /**
     * Create the single instance of class
     *
     * @return mixed self::$singleInstance Instance
     */
    public static function getInstance() : static {
        return new static();
    }

    /**
     * Keep the constructor private
     */
    private function __construct() { }

    /**
     * Stop serialization
     *
     * @throws NotSerializableException
     */
    public function __sleep() : array {
        throw new NotSerializableException('Serializing instances of this class is forbidden.');
    }

    /**
     * Stop serialization
     *
     * @throws NotSerializableException
     */
    public function __wakeup() : void {
        throw new NotSerializableException('Serializing and deserializing instances of singleton class is forbidden.');
    }

    /**
     * Override clone method to stop cloning of the object
     *
     * @throws CloneNotSupportedException
     */
    public function __clone() : void {
        throw new CloneNotSupportedException("Cloning is not supported in singleton class.");
    }
}