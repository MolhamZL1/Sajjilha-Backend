<?php

if (! function_exists('response_data')) {
    function response_data($data, $message = null, $status = 200)
    {
        return response([
            'result'  => !empty($data) ? $data : null,
            'message' => !empty($message) ? $message : __('main.msg_succes'),
            'StatusCode' => $status,
            'status' => in_array($status, [200, 201, 202, 203]),
        ], $status); 
    }
}

