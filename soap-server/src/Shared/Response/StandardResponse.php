<?php

namespace Src\Shared\Response;

/**
 *   Standard response class for SOAP responses
 *
 *
 */


class StandardResponse
{
    public static function success(string $message, array $data = [], ?int  $code = 200): array
    {
        return [
            'success' => true,
            'error_code' => null,
            'message' => $message,
            'data' => $data,
            'code' => $code
        ];
    }

    public static function error(string $message, ?string $errorCode = '', ?array $data = null, ?int $code = 500): array
    {
        return [
            'success' => false,
            'error_code' => $errorCode,
            'message' => $message,
            'data' => $data,
            'code' => $code
        ];
    }
}
