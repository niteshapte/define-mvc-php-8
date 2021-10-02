<?php
declare(strict_types=1);
namespace Define\Utilities;

if(DIRECT_ACCESS != true) die("Direct access is forbidden.");

use Throwable;

/**
 * ERROR EXCEPTION HANDLER INTERFACE
 *
 * Interface to handle and log the errors and exception occurs in the project.
 *
 * @category Define
 * @package Utilities
 * @author Nitesh Apte <me@niteshapte.com>
 * @copyright 2017 Nitesh Apte
 * @version 3.0.0
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
interface IErrorExceptionHandler extends IUtilities {

    /**
     * Set custom error handler and exception handler
     *
     * @return void
     */
    public function enableHandler() : void;

    /**
     * Custom error logging in custom format
     *
     * @param int $errNo
     * @param string $errStr
     * @param string $errFile
     * @param int $errLine
     */
    public function errorHandler(int $errNo, string $errStr, string $errFile, int $errLine) : void;

    /**
     * Custom exception handler
     *
     * @param Throwable $exception
     */
    public function exceptionHandler(Throwable $exception) : void;
}