<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class NotImplementedException extends HttpException
{
    public function __construct(string $message)
    {
        parent::__construct(
            Response::HTTP_NOT_IMPLEMENTED,
            $message
        );
    }
}
