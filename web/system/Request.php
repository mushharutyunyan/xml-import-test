<?php

namespace System;

use App\Errors\ValidationError;
use App\Responses\Response;

abstract class Request
{
    protected array $data;
    protected ValidationError $errors;

    public function __construct()
    {
        $this->data = $_GET + $_POST;
        if(empty($this->data)) {
            $json = file_get_contents('php://input');
            $this->data = $json ? json_decode($json, true) : [];
        }
        $this->errors = new ValidationError();
        if(!$this->validate()) {
            (new Response())::jsonErrors($this->errors);
        }
    }

    public function all(): array
    {
        return $this->data;
    }

    abstract function validate();
}