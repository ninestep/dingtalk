<?php


namespace Shenhou\Dingtalk;


use Throwable;

class DingTalkException extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}