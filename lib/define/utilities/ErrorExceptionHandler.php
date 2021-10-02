<?php
declare(strict_types=1);
namespace Define\Utilities;

if(DIRECT_ACCESS != true) die("Direct access is forbidden.");

use Define\Traits\SingletonTrait;
use Throwable;
use function error_reporting;
use function register_shutdown_function;
use function set_error_handler;
use function set_exception_handler;

/**
 * ERROR EXCEPTION HANDLER
 *
 * This class handles and logs the errors and exception occurs in the project.
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
class ErrorExceptionHandler implements IErrorExceptionHandler {

    // Singleton instance
    use SingletonTrait;

    /**
     * @var $MAXLENGTH int length for backtrace message
     * @see debugBacktrace()
     */
    private int $MAXLENGTH = 64;

    /**
     * @var $errorType array defined errors
     * @see customError()
     */
    private array $errorType;

    /**
     * @var array $exceptionType Custom exception number
     * @see exceptionHandler
     */
    private array $exceptionType;


    /**
     * Initiate the handlers
     *
     * @return void
     */
    private function __construct() {
        $this->errorType = include 'ErrorCodes.php';
        $this->exceptionType = include 'ExceptionCodes.php';
        $this->enableHandler();
    }

    /**
     * Set custom error handler and exception handler
     *
     * @return void
     */
    public function enableHandler() : void {
        error_reporting(1);
        set_exception_handler(array($this,	'exceptionHandler'));
        set_error_handler(array($this,'errorHandler'), APP_ERROR);
        register_shutdown_function(array($this, 'fatalError'));
    }

    /**
     * Custom error logging in custom format
     *
     * @param int $errNo Error number
     * @param string $errStr Error string
     * @param string $errFile Error file
     * @param int $errLine Error line
     * @return void
     */
    public function errorHandler(int $errNo, string $errStr, string $errFile, int $errLine) : void {
        if(error_reporting() == 0) {
            return;
        }
        $backTrace = $this->debugBacktrace(2);

        $logMessage = $this->_toStringForLogging($errNo, $errStr, $errFile, $errLine, $backTrace, 'Error', $this->errorType[$errNo]);
        $webMessage = $this->_toStringForWeb($errNo, $errStr, $errFile, $errLine, $backTrace, 'Error', $this->errorType[$errNo]);

        $this->debug($logMessage, $webMessage);
    }

    /**
     * Custom exception handler
     *
     * @param Throwable $exception
     * @return void
     */
    public function exceptionHandler(Throwable $exception) : void {
        while ($e = $exception->getPrevious()) {
            $exception = $e;
        }

        $logMessage = $this->_toStringForLogging($exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine(), $exception->getTraceAsString(), 'Exception', $this->exceptionType[$exception->getCode()]);
        $webMessage = $this->_toStringForWeb($exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine(), $exception->getTraceAsString(), 'Exception', $this->exceptionType[$exception->getCode()]);

        $this->debug($logMessage, $webMessage);
    }

    /**
     * Display the error
     *
     * @param string $logMessage Message for log file
     * @param string $webMessage Message to show on browser
     * @return void
     */
    private function debug(string $logMessage, string $webMessage) : void {
        SEND_ERROR_MAIL === TRUE ?? error_log($logMessage, 1, ADMIN_ERROR_MAIL, "From: ".SEND_ERROR_FROM."\r\nTo: ".ADMIN_ERROR_MAIL);
        ERROR_LOGGING === TRUE ?? error_log($logMessage, 3, LOG_FILE_PATH);
        echo DEBUGGING === TRUE ? $webMessage : SITE_GENERIC_ERROR_MSG;
        MODE == 'DEVELOPMENT' ?? exit();
    }

    /**
     * Build backtrace message
     *
     * @param int $entriesMade Irrelevant entries in debug_backtrace, first two characters
     * @return string
     */
    private function debugBacktrace(int $entriesMade) : string {

        $traceArray = debug_backtrace();
        $argsDefine = array();

        $traceMessage = '';

        for($i=0;$i<$entriesMade;$i++) {
            array_shift($traceArray);
        }

        foreach($traceArray as $newArray) {
            if(isset($newArray['class'])) {
                $traceMessage .= $newArray['class'].'.';
            }
            if(!empty($newArray['args'])) {

                foreach($newArray['args'] as $newValue) {
                    if(is_null($newValue)) {
                        $argsDefine[] = NULL;
                    } elseif(is_array($newValue)) {
                        $argsDefine[] = 'Array['.sizeof($newValue).']';
                    } elseif(is_object($newValue)) {
                        $argsDefine[] = 'Object: '.get_class($newValue);
                    } elseif(is_bool($newValue)) {
                        $argsDefine[] = $newValue ? 'TRUE' : 'FALSE';
                    } else {
                        $newValue = (string)@$newValue;
                        $stringValue = htmlspecialchars(substr($newValue, 0, $this->MAXLENGTH));
                        if(strlen($newValue)>$this->MAXLENGTH) {
                            $stringValue = '...';
                        }
                        $argsDefine[] = "\"".$stringValue."\"";
                    }
                }
            }
            $traceMessage .= $newArray['function'].'('.implode(',', $argsDefine).')';
            $lineNumber = ($newArray['line'] ?? "unknown");
            $fileName = ($newArray['file'] ?? "unknown");

            $traceMessage .= sprintf(" # line %4d. file: %s", $lineNumber, $fileName);
            $traceMessage .= "\n";
        }
        return $traceMessage;
    }

    /**
     * Method to catch fatal and parse error
     *
     * @return void
     */
    public function fatalError() : void {
        $lastError =  error_get_last();
        if (!empty($lastError)) {
            if($lastError['type'] == 1 || $lastError['type'] == 4 || $lastError['type'] == 16 || $lastError['type'] == 64 || $lastError['type'] == 256 || $lastError['type'] == 4096) {
                $this->errorHandler($lastError['type'], $lastError['message'], $lastError['file'], $lastError['line']);
            }
        }
    }

    /**
     * Decorate the message for browser
     *
     * @param int $errNo
     * @param string $errStr
     * @param string $errFile
     * @param int $errLine
     * @param string $backTrace
     * @param string $category
     * @param string $type
     * @return string
     */
    private function _toStringForWeb(int $errNo, string $errStr, string $errFile, int $errLine, string $backTrace, string $category, string $type) : string {
        $css = <<<EOT
		<style>
		.errormessage {
			margin:0;padding:0;
			width:100%;
		}
		.errormessage table{
		    border-collapse: collapse;
		    border-spacing: 0;
			width:100%;
			margin:0;padding:0;
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
		}.errormessage tr:last-child td{
			border-width:0 1px 0 0;
		}.errormessage tr td:last-child{
			border-width:0 0 1px 0;
		}.errormessage tr:last-child td:last-child{
			border-width:0 0 0 0;
		}
		.errorhead {
			font: 20px Arial;
			margin: 5px 0 10px 0;
			font-weight: bold;
		}
		</style>
EOT;
        $errorMessage = "<title>Website Generic Error and Exception Application - $type</title><div class='errorhead'>Website Generic Error and Exception Application</div><div class='errormessage'><table>";
        $errorMessage .= "<tr><td><b>CATEGORY : </b></td><td><span style=\"color: red; \">$category</span></td></tr>";
        $errorMessage .= "<tr><td><b>ERROR NO : </b></td><td><span style=\"color: red; \">$errNo</span></td></tr>";
        $errorMessage .= "<tr><td><b>ERROR TYPE : </b></td><td><i><b><span style=\"color: red; \">$type</span></b></i></td></tr>";
        $errorMessage .= "<tr><td><b>TEXT : </b></td><td><span>$errStr</span></td></tr>";
        $errorMessage .= "<tr><td><b>LOCATION : </b></td><td><span style=\"color: red; \">$errFile</span>, <b>line</b> $errLine, at " .date("F j, Y, g:i a")."</td></tr>";
        $errorMessage .= "<tr><td><b>Showing Backtrace : </b></td><td>$backTrace </td></tr></table></div>";
        $webMessage = str_replace("#", "<br />", $errorMessage);
        return $css.$webMessage;
    }

    /**
     * Decorate the message for the log file
     *
     * @param int $errNo
     * @param string $errStr
     * @param string $errFile
     * @param int $errLine
     * @param string $backTrace
     * @param string $category
     * @param string $type
     * @return string
     */
    private function _toStringForLogging(int $errNo, string $errStr, string $errFile, int $errLine, string $backTrace, string $category, string $type) : string {
        $logMessage = "=====================================================================================================================================\n";
        $logMessage .= "Website Generic Error!\n";
        $logMessage .= "=====================================================================================================================================\n";
        $logMessage .= "CATEGORY : $category\n";
        $logMessage .= "=====================================================================================================================================\n";
        $logMessage .= "ERROR NO : $errNo\n";
        $logMessage .= "=====================================================================================================================================\n";
        $logMessage .= "ERROR TYPE : $type\n";
        $logMessage .= "=====================================================================================================================================\n";
        $logMessage .= "TEXT : $errStr\n";
        $logMessage .= "=====================================================================================================================================\n";
        $logMessage .= "LOCATION : $errFile, line $errLine, at ".date("F j, Y, g:i a")."\n";
        $logMessage .= "=====================================================================================================================================\n";
        $logMessage .= "Showing Backtrace : \n$backTrace \n";
        $logMessage .= "=====================================================================================================================================\n\n";
        return $logMessage;
    }
}