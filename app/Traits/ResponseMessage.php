<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

trait ResponseMessage
{

    /**
     * @param string $message
     * @param mixed $data
     * @param int $code
     * @return Response
     */
    public function successResponse(string $message = 'Success', mixed $data = [], int $code = 200) :Response
    {
        return response([
            'status' => 'success',
            'code' => $code,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * @param string $message
     * @param int $code
     * @return Response
     */
    public function failedResponse(string $message = 'Failed To fetch data', int $code = 400): Response
    {
        return response([
            'status' => 'error',
            'code' => $code,
            'message' => $message,
        ], $code);
    }


        /**
     * Handle a failed validation attempt.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function handleFailedValidation($validator): JsonResponse
    {
        throw new ValidationException(
            $validator,
            response()->json([
                'status' => 'error',
                'code' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
                'errors' => $validator->errors(),
                'message' => 'Error while validating data.',
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }

    /**
     * @param string $message
     * @param mixed $data
     * @param int $code
     * @return Response
     */
    public function successResponseCollection(string $message = 'Success', mixed  $data = [], int $code = 200): Response
    {
        $result = [
            'status' => 'success',
            'code' => $code,
            'message' => $message,
        ];
        if (isset($data)) {
            foreach ($data as $k => $v) {
                if ($k !== 'data') {
                    $result[$k] = $v;
                }
            }
        }
        if (isset($data['data'])){
            $result['data']=$data['data'];
        }
        return response($result,$code);
    }

    /**
     * @param string $message
     * @param mixed  $data
     * @param int $code
     * @return Response
     */
    public function failResponseCollection(string $message = 'Failed', mixed  $data=[], int $code =400) : Response
    {
        $result = [
            'status' => 'error',
            'code' => $code,
            'message' => $message,
        ];
        if (isset($data)) {
            foreach ($data as $k => $v) {
                if ($k !== 'data') {
                    $result[$k] = $v;
                }
            }
        }
        if (isset($data['data'])){
            $result['data']=$data['data'];
        }
        return response($result,$code);
    }

    /**
     * @param \Throwable $th
     * @param $message
     * @return string
     */
    public function errorMessage(\Throwable $th, $message): string
    {
        return env('APP_DEBUG') ? $th->getMessage() : $message;
    }
}
