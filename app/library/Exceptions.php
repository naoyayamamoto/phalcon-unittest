<?php

// エラーをはくクラス
class Exceptions
{
    public static function throw($message = '', $code = 1) {
        throw new \Exception($message, $code);
    }
}
