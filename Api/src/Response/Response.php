<?php

namespace Api\Response;

class Response
{
    private int $status;
    private mixed $message;
    private array $errors;

    public function __construct($status, $message, $errors = [])
    {
        $this->status = $status;
        $this->message = $message;
        $this->errors = $errors;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getMessage(): mixed
    {
        return $this->message;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
