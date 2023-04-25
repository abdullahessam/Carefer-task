<?php


function response_success($data, $status = 200, $headers = [], $options = 0)
{
    return response([
        'data' => $data,
        'message' => 'success',
        'status' => $status
    ], $status, $headers, $options);
}

function response_error($data, $status = 200, $headers = [], $options = 0)
{
    return response([
        'data' => $data,
        'message' => 'error',
        'status' => $status
    ], $status, $headers, $options);
}
