<?php
declare(strict_types=1);
namespace Define\Core;

use ArrayAccess;
use Define\Exceptions\FrameworkException;
use Define\Traits\SingletonTrait;

if(DIRECT_ACCESS != true) die("Direct access is forbidden.");

/**
 * REGISTER OBJECT
 *
 * SuperObject objectHolder class. objectHolder pattern.
 * All the instances created will be stored in this container and will be called when needed.
 *
 * @category Define
 * @package Core
 * @author Nitesh Apte <me@niteshapte.com>
 * @copyright 2015 Nitesh Apte
 * @version 1.1.0
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
final class RegisterObject implements IDefine, ArrayAccess {

    use SingletonTrait;

    /**
     * @var array
     */
    private static array $registry = array();

    /**
     * Check if an object has been already registered or not.
     *
     * @param mixed $offset Name of the object.
     * @return boolean
     */
    public function offsetExists(mixed $offset): bool {
        return isset(self::$registry[$offset]);
    }

    /**
     * Get an object
     *
     * @param String $offset Name of the object.
     * @return mixed
     * @throws FrameworkException if object has not been registered.
     */
    public function offsetGet(mixed $offset): mixed {
        if(!isset(self::$registry[$offset])) {
            throw new FrameworkException("Object for key $offset has not been set.");
        }
        return self::$registry[$offset];
    }

    /**
     * Set an object
     *
     * @param mixed $offset Name of the object.
     * @param Object $value Object to be registered.
     * @return void
     * @throws FrameworkException If object has been already registered.
     */
    public function offsetSet(mixed $offset, mixed $value) : void {
        if(isset(self::$registry[$offset])) {
            throw new FrameworkException("Object for key $offset has already been set.");
        }
        self::$registry[$offset] = $value;
    }

    /**
     * Unset an object from the registry
     *
     * @param String $offset Name of the object.
     * @return void
     */
    public function offsetUnset(mixed $offset) : void {
        unset(self::$registry[$offset]);
    }

    /**
     * Get all the instances of a particular type.
     *
     * @param Object $instanceof
     * @return array
     */
    public function allOffsetGet(object $instanceof) : array {
        $objects = array();
        foreach (self::$registry as $value) {
            if($value instanceof $instanceof) {
                $objects[] = $value;
            }
        }
        return $objects;
    }
}
