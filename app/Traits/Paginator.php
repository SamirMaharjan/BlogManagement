<?php


namespace App\Traits;


use Illuminate\Pagination\LengthAwarePaginator;

trait Paginator
{
    public function links($data) :array
    {
        if ($data instanceof LengthAwarePaginator){
            return [
                'first' => $data->url(1),
                'last' => $data->url($data->lastPage()),
                'prev' => $data->previousPageUrl(),
                'next' => $data->nextPageUrl(),
            ];
        }

    }

    public function meta($data) :array
    {
        if ($data instanceof LengthAwarePaginator){
            return [
                'current_page' => $data->currentPage(),
                'from' => $data->firstItem(),
                'last_page' => $data->lastPage(),
                'path' => $data->path(),
                'per_page' => $data->perPage(),
                'to' => $data->lastItem(),
                'total' => $data->total(),
            ];
        }

    }
}
