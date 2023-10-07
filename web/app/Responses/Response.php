<?php

namespace App\Responses;

use App\Errors\ValidationError;
use JetBrains\PhpStorm\NoReturn;

class Response
{
    public static function jsonOk(array $data): void
    {
        header('Content-type: application/json');
        http_response_code(200);
        echo json_encode(['data' => $data]);
        exit;
    }

    public static function jsonErrors(ValidationError $errors): void
    {
        header('Content-type: application/json');
        http_response_code(422);
        echo json_encode([
            'message' => $errors->getMessage(),
            'errors' => $errors->getErrors()
        ]);
        exit;
    }

    public static function jsonError(\Exception $exception): void
    {
        header('Content-type: application/json');
        http_response_code(444);
        echo json_encode([
            'message' => $exception->getMessage(),
            'errors' => 444
        ]);
        exit;
    }
}