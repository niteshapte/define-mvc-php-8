<?php
declare(strict_types=1);
namespace Define\Exceptions;

if(DIRECT_ACCESS != true) die("Direct access is forbidden.");

class NullPointerException extends FrameworkException { }