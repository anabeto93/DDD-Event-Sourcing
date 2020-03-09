<?php

if (!function_exists('baseResponse')) {
    /**
     * Base response to return
     * @param string $status
     * @param string $message
     * @param int $error_code
     * @param array $data
     * @return array
     */
    function baseResponse($status, $message, $error_code, $data=[]) : array {
        $result = [
            'status' => ucfirst($status),
            'message' => $message,
            'error_code' => $error_code,
        ];

        if (is_array($data) && !empty($data)) {
            $result['data'] = $data;
        }

        return $result;
    }
}

if (!function_exists('successResponse')) {
    /**
     * Success response to return
     * @param string $message
     * @param int $error_code
     * @param array $data
     * @return array
     */
    function successResponse($message, $error_code=200, $data=[]) : array {
        return baseResponse('Success', $message, $error_code, $data);
    }
}

if (!function_exists('errorResponse')) {
    /**
     * Error or Failed response
     * @param string $message
     * @param int $error_code
     * @param array $data
     * @return array
     */
    function errorResponse($message, $error_code=400, $data=[]) : array {
        return baseResponse('Declined', $message, $error_code, $data);
    }
}

if (!function_exists('sendHttpRequest')) {
    /**
     * Make an Http Request with the given params
     * @param string $url
     * @param string $method
     * @param array $body
     * @param string $body_type
     * @param array $headers
     * @param boolean $verify_ssl
     * @return array
     */
    function sendHttpRequest($url, $method, $body=[], $body_type='json', $headers=[], $verify_ssl=false) {
        $head = [];
        $content = [];
        $method = strtolower($method);
        \Illuminate\Support\Facades\Log::debug('payload to send', ['url' => $url, 'method' => $method, 'payload' => $body, 'headers' => $headers]);

        if (!empty($headers)) {
            $head = $headers;
        }

        //check for data_types
        if ($body_type === 'json') {
            if (!array_key_exists('content-type', $head)) {
                $header['content-type'] = 'application/json';
                $header['accept'] = 'application/json';
            }
        } elseif ($body_type === 'form_params') {
            if (!array_key_exists('content-type', $head)) {
                $header['content-type'] = 'application/x-www-form-urlencoded';
            }
        }

        if(!empty($body)) {
            $content = $body;
        }

        try {
            if ($method === 'post') {
                //check if headers not empty
                if(empty($head) && $body_type == 'form_params') {
                    $response = \Zttp\Zttp::withHeaders($head)->asFormParams()->withOptions(['verify' => $verify_ssl])->post($url, $content);
                } elseif (empty($head) && $body_type == 'json') {
                    $response = \Zttp\Zttp::post($url, $content);
                } elseif(!empty($head) && $body_type == 'json') {
                    $response = \Zttp\Zttp::withHeaders($head)->withOptions(['verify' => $verify_ssl])->post($url, $content);
                } elseif(!empty($head) && $body_type == 'form_params') {
                    $response = \Zttp\Zttp::asFormParams()->withHeaders($head)->withOptions(['verify' => $verify_ssl])->post($url, $content);
                } else {
                    $response = \Zttp\Zttp::withHeaders($head)->withOptions(['verify' => $verify_ssl])->post($url, $content);
                }
            } elseif ($method === 'put') {
                //check if headers not empty
                if (empty($head) && $body_type == 'form_params') {
                    $response = \Zttp\Zttp::asFormParams()->withOptions(['verify' => $verify_ssl])->put($url, $content);
                } elseif(empty($head) && $body_type == 'json') {
                    $response = \Zttp\Zttp::put($url, $content);
                } elseif(!empty($head) && $body_type == 'form_params') {
                    $response = \Zttp\Zttp::withHeaders($head)->withOptions(['verify' => $verify_ssl])->put($url, $content);
                } elseif(!empty($head) && $body_type == 'json') {
                    $response = \Zttp\Zttp::withHeaders($head)->withOptions(['verify' => $verify_ssl])->put($url, $content);
                } else {
                    $response = \Zttp\Zttp::withHeaders($head)->withOptions(['verify' => $verify_ssl])->put($url, $content);
                }
            } elseif ($method === 'patch') {
                //check if headers not empty
                if (empty($head) && $body_type == 'form_params') {
                    $response = \Zttp\Zttp::asFormParams()->withOptions(['verify' => $verify_ssl])->patch($url, $content);
                } elseif(empty($head) && $body_type == 'json') {
                    $response = \Zttp\Zttp::patch($url, $content);
                } elseif(!empty($head) && $body_type == 'form_params') {
                    $response = \Zttp\Zttp::withHeaders($head)->withOptions(['verify' => $verify_ssl])->patch($url, $content);
                } elseif(!empty($head) && $body_type == 'json') {
                    $response = \Zttp\Zttp::withHeaders($head)->withOptions(['verify' => $verify_ssl])->patch($url, $content);
                } else {
                    $response = \Zttp\Zttp::withHeaders($head)->withOptions(['verify' => $verify_ssl])->patch($url, $content);
                }
            } elseif ($method === 'delete') {
                if (empty($head) && $body_type == 'form_params') {
                    $response = \Zttp\Zttp::asFormParams()->delete($url, $content);
                } elseif(empty($head) && $body_type == 'json') {
                    $response = \Zttp\Zttp::delete($url, $content);
                } elseif (!empty($head) && $body_type == 'form_params') {
                    $response = \Zttp\Zttp::withHeaders($head)->asFormParams()->delete($url, $content);
                } elseif (!empty($head) && $body_type == 'json') {
                    $response = \Zttp\Zttp::withHeaders($head)->delete($url, $content);
                } else {
                    $response = \Zttp\Zttp::withHeaders($head)->delete($url, $content);
                }
            } else {
                //assume it is a get request
                if (empty($head) && $body_type == 'form_params') {
                    $response = \Zttp\Zttp::asFormParams()->withOptions(['verify' => $verify_ssl])->get($url, $content);
                } elseif (!empty($head) && $body_type == 'form_params') {
                    $response = \Zttp\Zttp::withHeaders($head)->asFormParams()->withOptions(['verify' => $verify_ssl])->get($url, $content);
                } else {
                    $response = \Zttp\Zttp::withHeaders($head)->withOptions(['verify' => $verify_ssl])->get($url, $content);
                }
            }
        } catch (\Zttp\ConnectionException | \GuzzleHttp\Exception\ConnectException $e) {
            $error = new \Support\ServiceResponse('Declined', 'Connection error occurred.', 500, ['url' => $url]);
            throw new \Illuminate\Http\Exceptions\HttpResponseException(response()->json($error->toArray(), 500));
        }

        if (!$response->json() && $response->getStatusCode() != 200) {
            return errorResponse($response->getReasonPhrase(), $response->getStatusCode());
        }

        return $response->json();
    }
}

if (!function_exists('getHttpRequest')) {
    /**
     * @param string $url
     * @param array $body
     * @param array $headers
     * @param string $body_type
     * @param boolean $verify_ssl
     * @return array
     */
    function getHttpRequest($url, $body=[], $headers=[], $body_type='form_params', $verify_ssl=false) {
        return sendHttpRequest($url, 'get', $body, $body_type, $headers, $verify_ssl);
    }
}

if (!function_exists('postHttpRequest')) {
    /**
     * @param string $url
     * @param array $body
     * @param array $headers
     * @param string $body_type
     * @param boolean $verify_ssl
     * @return array
     */
    function postHttpRequest($url, $body=[], $headers=[], $body_type='json', $verify_ssl=false) {
        if (empty($headers) && $body_type == 'json') {
            $headers = [
                'content-type' => 'application/json', 'accept' => 'application/json', 'charset' => 'utf-8'
            ];
        }
        return sendHttpRequest($url, 'post', $body, $body_type, $headers, $verify_ssl);
    }
}

if (!function_exists('putHttpRequest')) {
    /**
     * @param string $url
     * @param array $body
     * @param array $headers
     * @param string $body_type
     * @param boolean $verify_ssl
     * @return array
     */
    function putHttpRequest($url, $body=[], $headers=[], $body_type='json', $verify_ssl=false) {
        if (empty($headers) && $body_type == 'json') {
            $headers = [
                'content-type' => 'application/json', 'accept' => 'application/json', 'charset' => 'utf-8'
            ];
        }
        return sendHttpRequest($url, 'put', $body, $body_type, $headers, $verify_ssl);
    }
}

if (!function_exists('patchHttpRequest')) {
    /**
     * @param string $url
     * @param array $body
     * @param array $headers
     * @param string $body_type
     * @param boolean $verify_ssl
     * @return array
     */
    function patchHttpRequest($url, $body=[], $headers=[], $body_type='json', $verify_ssl=false) {
        if (empty($headers) && $body_type == 'json') {
            $headers = [
                'content-type' => 'application/json', 'accept' => 'application/json', 'charset' => 'utf-8'
            ];
        }
        return sendHttpRequest($url, 'patch', $body, $body_type, $headers, $verify_ssl);
    }
}

if (!function_exists('deleteHttpRequest')) {
    /**
     * @param string $url
     * @param array $body
     * @param array $headers
     * @param string $body_type
     * @param boolean $verify_ssl
     * @return array
     */
    function deleteHttpRequest($url, $body=[], $headers=[], $body_type='json', $verify_ssl=false) {
        return sendHttpRequest($url, 'delete', $body, $body_type, $headers, $verify_ssl);
    }
}

if (!function_exists('minorToFloat')) {
    /**
     * @param string|int $amount
     * @return float
     */
    function minorToFloat($amount) : float {
        return ((float) $amount / 100.0 );
    }
}

if (!function_exists('minorToInt')) {
    /**
     * @param string $amount in minor units
     * @return int
     */
    function minorToInt(string $amount) : int {
        return (int) (minorToFloat($amount) * 100);
    }
}

if (!function_exists('floatToMinor')) {
    /**
     * @param float|int $amount
     * @return string
     */
    function floatToMinor($amount) : string {
        //multiply it by 100 first
        //based on the length, append
        $val = ((string) $amount * 100);
        //pad it with zeros
        $iter = 12 - strlen($val);
        return str_repeat('0', $iter) . $val;//This is according to the amount in ISO 8583 standards
    }
}

if (!function_exists('checkLuhn')) {
    /**
     * Check validity of PAN passed using Luhn's algorithm
     * @param string $card_number
     * @return bool
     */
    function checkLuhn(string $card_number): bool {
        $sum = 0;
        $flag = 0;

        for ($i = strlen($card_number) - 1; $i >= 0; $i--) {
            $add = $flag++ & 1 ? $card_number[$i] * 2 : $card_number[$i];
            $sum += $add > 9 ? $add - 9 : $add;
        }

        return $sum % 10 === 0;
    }

}

if (!function_exists('now')) {
    /**
     * Simply the now() helper in Laravel
     */
    function now() {
        return \Carbon\Carbon::now();
    }
}

if (! function_exists('request')) {
    /**
     * Get an instance of the current request or an input item from the request.
     *
     * @param  array|string  $key
     * @param  mixed   $default
     * @return \Illuminate\Http\Request|string|array
     */
    function request($key = null, $default = null) {
        if (is_null($key)) {
            return app('request');
        }

        if (is_array($key)) {
            return app('request')->only($key);
        }

        $value = app('request')->__get($key);

        return is_null($value) ? value($default) : $value;
    }
}

if (!function_exists('createSignature')) {
    /**
     * Create a signed signature based on the parameters
     * @param string $data
     * @param string $algorithm
     * @return string
     */
    function createSignature(string $data, $algorithm='sha256') {
        return base64_encode(hash($algorithm, $data, true));
    }
}

if (! function_exists('auth')) {
    /**
     * Get the available auth instance.
     *
     * @param  string|null  $guard
     * @return \Illuminate\Contracts\Auth\Factory|\Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
     */
    function auth($guard = null) {
        if (is_null($guard)) {
            return app(\Illuminate\Contracts\Auth\Factory::class);
        }

        return app(\Illuminate\Contracts\Auth\Factory::class)->guard($guard);
    }
}

if (!function_exists('back')) {
    /**
     * Create a new redirect response to the previous location.
     *
     * @param int $status
     * @param array $headers
     * @param mixed $fallback
     *
     * @return RedirectResponse
     */
    function back($status = 302, $headers = [], $fallback = false) {
        return redirect()->back($status, $headers, $fallback);
    }
}

if (!function_exists('maskPan')) {
    /**
     * Mask PAN for PCI-Compliance
     * @param string $card_number
     * @return string
     */
    function maskPan($card_number) : string {
        $bin = substr($card_number, 0, 6);//either first 4 or 6
        $a_num = substr($card_number, -4);

        $rem = strlen($card_number) - (strlen($bin) + strlen($a_num));

        return $bin . str_repeat('*', $rem) . $a_num;
    }
}

if (!function_exists('parseJWT')) {
    /**
     * Decode the JWT and return the claims only as array
     * @param string $jwt_token
     * @return array
     */
    function parseJWT($jwt_token) : array {
        return json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $jwt_token)[1]))), true);
    }
}

if (!function_exists('emptyStr')) {
    /**
     * Check if a string is empty or not, especially for config values
     * @param string $string
     * @return bool
     */
    function emptyStr($string): bool {
        return is_string($string) && strlen(trim($string)) === 0;
    }
}
