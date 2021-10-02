<?php
declare(strict_types=1);
namespace Application\Exceptions;
use Define\Exceptions\FrameworkException;

if(DIRECT_ACCESS != true) die("Direct access is forbidden.");

/**
 * APPLICATION EXCEPTION
 *
 * This is the base exception class for application to be developed. Extends FrameworkException class.
 * Override or add default methods for your application.
 *
 * @category Application
 * @package Exceptions
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
class ApplicationException extends FrameworkException {

    /**
     * @param ?string $message
     * @param int $code
     */
    public function __construct(?string $message, int $code = 0) {
        parent::__construct($message, $code);
    }
}