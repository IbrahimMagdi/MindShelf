<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Shared\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;

abstract class ApiController extends Controller
{
    protected function success(
        mixed $data = null,
        string $message = 'Success',
        int $status = 200
    ): JsonResponse {
        return ApiResponse::success($data, $message, $status);
    }

    protected function created(
        mixed $data = null,
        string $message = 'Created successfully'
    ): JsonResponse {
        return ApiResponse::created($data, $message);
    }

    protected function noContent(
        string $message = 'Deleted successfully'
    ): JsonResponse {
        return ApiResponse::noContent($message);
    }

    protected function error(
        string $message = 'Error',
        int $status = 400
    ): JsonResponse {
        return ApiResponse::error($message, $status);
    }

    protected function notFound(
        string $message = 'Resource not found'
    ): JsonResponse {
        return ApiResponse::notFound($message);
    }

    protected function unauthorized(
        string $message = 'Unauthorized'
    ): JsonResponse {
        return ApiResponse::unauthorized($message);
    }

    protected function forbidden(
        string $message = 'Forbidden'
    ): JsonResponse {
        return ApiResponse::forbidden($message);
    }

    protected function validation(array $errors): JsonResponse
    {
        return ApiResponse::validation($errors);
    }
}
