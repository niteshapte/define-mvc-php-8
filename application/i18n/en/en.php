<?php
declare(strict_types = 1);
namespace Application\i18n;

if(DIRECT_ACCESS != true) die("Direct access is forbidden.");

return array(
    // Error page //
    "400" => "Bad Request.",
    "401" => "Unauthorized Access.",
    "402" => "Payment Required.",
    "403" => "Forbidden. You are not allowed to access this page.",
    "408" => "Request Timeout.",
    "415" => "Unsupported Media Type.",
    "500" => "Internal Server Error.",
    "502" => "Bad Gateway.",
    "503" => "Service Unavailable.",
    "404" => "Page Not Found! <BR><BR>Oops...Guess you were looking for something else."
);