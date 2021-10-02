<?php
declare(strict_types=1);

if(DIRECT_ACCESS != true) die("Direct access is forbidden.");

########################################################################################################

#-- SETTING FOR FRAMEWORK STARTS --#
define("BASE_PATH", $_SERVER['DOCUMENT_ROOT']);
const APP_PATH = BASE_PATH . 'application/';
const CONTROLLER_PATH = APP_PATH . 'controller/';
const CONTROLLER_PREFIX = '';
const CONTROLLER_SUFFIX = 'Controller';
const CONTROLLER_NAMESPACE = 'Application\\Controller\\';    // This is the namespace of the controller that you will define. If you don't want to define, make it blank('')
const DEFAULT_CONTROLLER = 'Index';
const DEFAULT_ACTION = 'default';
const ERROR_CONTROLLER = 'Error';
const NEED_SLASH = '/';    // Make it blank if you don't need / at the end of your url.
const ACTION_SUFFIX = 'Action';
const VIEW_PATH = APP_PATH . 'view/';
const SEPARATOR = "/"; // It has to be / because in case we are using multi words for controller or action then we need "-"
#-- SETTING FOR FRAMEWORK ENDS --#

########################################################################################################

#-- SETTINGS FOR ERROR AND EXCEPTION HANDLER STARTS --#
const MODE = 'DEVELOPMENT';
const APP_ERROR = E_ALL; // Development mode
const DEBUGGING = TRUE; // Development mode
const ADMIN_ERROR_MAIL = 'administrator@example.com';
const SEND_ERROR_MAIL = FALSE;
const SEND_ERROR_FROM = 'errors@example.com';
const ERROR_LOGGING = TRUE;
const LOG_FILE_PATH = BASE_PATH . 'logs/error.log'; // Please provide 777 permission to this folder
const SITE_GENERIC_ERROR_MSG = '<h1>Something wrong, sorry. Error!</h1>';
#-- SETTINGS FOR ERROR AND EXCEPTION HANDLER ENDS --#

########################################################################################################

#-- SETTING FOR LOGGER STARTS --#
const INFO_LOG_PATH = BASE_PATH. "/logs/info.log";
const DEBUG_LOG_PATH = BASE_PATH. "/logs/info.log";
const ERROR_LOG_PATH = BASE_PATH. "/logs/info.log";
const TRACE_LOG_PATH = BASE_PATH. "/logs/info.log";
#-- SETTING FOR LOGGER ENDS --#

########################################################################################################

#-- SETTINGS FOR LOCALIZATION STARTS --#
const DEFAULT_LANGUAGE = 'en';
const LOCALIZATION_PATH = APP_PATH . 'i18n/';
#-- SETTINGS FOR LOCALIZATION ENDS --#
