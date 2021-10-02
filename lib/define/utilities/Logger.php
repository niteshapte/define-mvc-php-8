<?php
declare(strict_types=1);
namespace Define\Utilities;

use Define\Exceptions\FrameworkException;
use Define\Traits\SingletonTrait;

if(DIRECT_ACCESS != true) die("Direct access is forbidden.");

/**
 * LOGGER
 *
 * Logger class for info, debug, error, trace
 *
 * @category Define
 * @package Utilities
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
class Logger implements IUtilities {

    use SingletonTrait;

    /**
     * Logs info messages
     *
     * @param string $message
     * @return void
     */
    public function info(string $message) : void {
        error_log($message . "\n", 3, INFO_LOG_PATH);
    }

    /**
     * Logs debug messages
     *
     * @param string $message
     * @return void
     */
    public function debug(string $message) : void {
        error_log($message . "\n", 3, DEBUG_LOG_PATH);
    }

    /**
     * Logs error messages
     *
     * @param string $message
     * @param FrameworkException $fe
     * @return void
     */
    public function error(string $message, FrameworkException $fe) : void {
        error_log($message. " Error: ".$fe->getMessage() . "\n", 3, ERROR_LOG_PATH);
    }

    /**
     * Logs trace messages
     *
     * @param string $message
     * @param FrameworkException $fe
     * @return void
     */
    public function trace(string $message, FrameworkException $fe) : void {
        error_log($message." Trace: ".$fe->getTraceAsString() . "\n", 3, TRACE_LOG_PATH);
    }
}