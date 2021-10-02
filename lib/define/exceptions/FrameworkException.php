<?php
declare(strict_types=1);
namespace Define\Exceptions;

use Exception;

if(DIRECT_ACCESS != true) die("Direct access is forbidden.");

/**
 * FRAMEWORK EXCEPTION
 *
 * Custom Exception class for the framework
 *
 * @category Define
 * @package Exceptions
 * @author Nitesh Apte <me@niteshapte.com>
 * @copyright 2021 Nitesh Apte
 * @version 3.0
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
class FrameworkException extends Exception implements IException {

    /**
     * Override the constructor
     *
     * @param string|null $message
     * @param int $code
     */
    public function __construct(string $message = null, int $code = 0) {
        $this->code = $code;
        if (!$message) {
            throw new $this('Unknown '. get_class($this));
        }
        parent::__construct($message, $code);
    }

    /**
     * {@inheritDoc}
     * @see Exception::__toString()
     */
    public function __toString() : string {
        return $this->formatMessage($this);
    }

    /**
     * Customize the message
     *
     * @param Exception $exception
     * @return string
     */
    public function formatMessage(Exception $exception) : string {
        $css = <<<EOT
		<style>
		.errormessage {
			margin:0;
			padding:0;
			width:100%;
		}
		.errormessage table{
		    border-collapse: collapse;
		    border-spacing: 0;
			width:100%;
			margin:0;
			padding:0;
		}
		.errormessage tr:last-child td:last-child {
			-moz-border-radius-bottomright:0;
			-webkit-border-bottom-right-radius:0;
			border-bottom-right-radius:0;
		}
		.errormessage table tr:first-child td:first-child {
			-moz-border-radius-topleft:0;
			-webkit-border-top-left-radius:0;
			border-top-left-radius:0;
		}
		.errormessage table tr:first-child td:last-child {
			-moz-border-radius-topright:0;
			-webkit-border-top-right-radius:0;
			border-top-right-radius:0;
		}
		.errormessage tr:last-child td:first-child{
			-moz-border-radius-bottomleft:0;
			-webkit-border-bottom-left-radius:0;
			border-bottom-left-radius:0;
		}
		.errormessage tr:hover td{
	
		}
		.errormessage tr:nth-child(odd){
			background-color:#e5e5e5;
		}
		.errormessage tr:nth-child(even) {
			background-color:#ffffff;
		}.errormessage td {
			vertical-align:middle;
			border-width:0 1px 1px 0;
			text-align:left;
			padding:5px;
			font-size:12px;
			font-weight:normal;
			color:#000000;
		}
		.errormessage tr:last-child td{
			border-width:0 1px 0 0;
		}
		.errormessage tr td:last-child{
			border-width:0 0 1px 0;
		}
		.errormessage tr:last-child td:last-child{
			border-width:0 0 0 0;
		}
		.error-head {
			font: 21px Arial;
			margin: 5px 0 11px 0;
			font-weight: bold;
		}
		</style>
EOT;
        $message = "<title>Generic Exception - {$exception->getMessage()}</title><div class='errorhead'>Website Generic Exception</div><div class='errormessage'><table style='border: 1px solid #000000;'>";
        $message .= "<tr><td><b>ERROR NO : </b></td><td><span style='color: red'>{$exception->getCode()}</span></td></tr>";
        $message .= "<tr><td><b>CLASS NAME : </b></td><td><i><b><span style='color: red'>".get_class($exception)."</span></b></i></td></tr>";
        $message .= "<tr><td><b>TEXT : </b></td><td><span style='color: red'>{$exception->getMessage()}</span></td></tr>";
        $message .= "<tr><td><b>LOCATION : </b></td><td><span style='color: red'>{$exception->getFile()}</span>, <b>line</b> {$exception->getLine()}, at ".date("F j, Y, g:i a")."</td></tr>";
        $message .= "<tr><td><b>Showing Backtrace : </b></td><td>{$exception->getTraceAsString()} </td></tr></table></div>";
        $webMessage = str_replace("#", "<br />", $message);
        return $css.$webMessage;
    }
}