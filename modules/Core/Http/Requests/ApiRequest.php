<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * API Request
 *
 * created at 05/08/2022
 * @author khuongtq
 */
class ApiRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator);
    }

    protected function failedAuthorization()
    {
        throw new HttpException(403);
    }
}
