<?php
namespace App\Errors;

class ValidationError
{
    private bool $enabled;
    private string $message = '';
    private array $errors = [];

    public function __construct()
    {
        $this->enabled = true;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function pushError(string $error): void
    {
        $this->errors[] = $error;
    }


}