<?php

namespace App\RequestHandler;

use Symfony\Component\HttpFoundation\Request;

interface RequestHandlerInterface
{
    /**
     * @throws \Exception
     */
    public function handle(Request $request): void;
}
