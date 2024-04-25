<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponse
{
    /**
     * Response with status code 200.
     *
     * @param        $data
     * @param string $msg
     *
     * @return JsonResponse
     */
    public function ok($data, string $msg = ''): JsonResponse
    {
        return $this->success($data, Response::HTTP_OK, $msg);
    }

    /**
     * @param        $data
     * @param string $msg
     * @param int $code
     *
     * @return JsonResponse
     */
    public function success($data, string $msg = '', int $code = Response::HTTP_OK): JsonResponse
    {
        $status = 'success';
        $response = $this->prepareResponse($status, $msg, $code);
        $response['results'] = $data;
        return response()->json($response, $code);
    }

    /**
     * Prepare response.
     *
     * @param string      $status
     * @param null|string $msg
     * @param int         $code
     *
     * @return array
     */
    protected function prepareResponse(string $status = 'success', ?string $msg = '', int $code = Response::HTTP_OK): array
    {
        if (empty($msg)) {
            $msg = Response::$statusTexts[$code];
        }
        return [
            'status' => $status,
            'message' => $msg,
        ];
    }

    /**
     * Response with status code 201.
     *
     * @param        $data
     * @param string $msg
     *
     * @return JsonResponse
     */
    public function created($data, string $msg = ''): JsonResponse
    {
        return $this->success($data, Response::HTTP_CREATED, $msg);
    }

    /**
     * Response with status code 400.
     *
     * @param        $data
     * @param string $msg
     *
     * @return JsonResponse
     */
    public function badRequest($data, string $msg = ''): JsonResponse
    {
        return $this->error($data, Response::HTTP_BAD_REQUEST, $msg);
    }

    /**
     * Error Response
     *
     * @param             $errors
     * @param null|string $msg
     * @param int         $code
     *
     * @return JsonResponse
     */
    public function error($errors, ?string $msg = '', int $code = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
    {
        $status = 'failure';
        $response = $this->prepareResponse($status, $msg, $code);
        $response['errors'] = $errors;
        return response()->json($response, $code);
    }

    /**
     * Response with status code 401.
     *
     * @param        $data
     * @param string $msg
     *
     * @return JsonResponse
     */
    public function unauthorized($data, string $msg = ''): JsonResponse
    {
        return $this->error($data, Response::HTTP_UNAUTHORIZED, $msg);
    }

    /**
     * Response with status code 403.
     *
     * @param        $data
     * @param string $msg
     *
     * @return JsonResponse
     */
    public function forbidden($data, string $msg = ''): JsonResponse
    {
        return $this->error($data, $msg, Response::HTTP_FORBIDDEN);
    }

    /**
     * Response with status code 404.
     *
     * @param        $data
     * @param string $msg
     *
     * @return JsonResponse
     */
    public function notFound($data, string $msg = ''): JsonResponse
    {
        return $this->error($data, Response::HTTP_NOT_FOUND, $msg);
    }

    /**
     * Response with status code 409.
     *
     * @param        $data
     * @param string $msg
     *
     * @return JsonResponse
     */
    public function conflict($data, string $msg = ''): JsonResponse
    {
        return $this->error($data, Response::HTTP_CONFLICT, $msg);
    }

    /**
     * Response with status code 422.
     *
     * @param        $data
     * @param string $msg
     *
     * @return JsonResponse
     */
    public function unprocessable($data, string $msg = ''): JsonResponse
    {
        return $this->error($data, Response::HTTP_UNPROCESSABLE_ENTITY, $msg);
    }
}
