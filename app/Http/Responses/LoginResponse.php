<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Symfony\Component\HttpFoundation\Response;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request): Response
    {
        if ($request->wantsJson()) {
            return response()->json(['two_factor' => false]);
        }

        $destination = $request->user()->isStaff()
            ? route('admin.dashboard', absolute: false)
            : route('home', absolute: false);

        return redirect()->to($destination);
    }
}
