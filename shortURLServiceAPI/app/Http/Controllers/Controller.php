<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Exceptions\AuthException;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function validateAuthUser()
    {
        if (is_null(auth()->user())) {
            throw new AuthException('Unauthorized');
        }
    }
}
