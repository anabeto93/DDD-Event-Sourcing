<?php

namespace Support;

class ServiceResponse 
{
    /**
     * The status message that determines whether it is failed or a successful response
     * @var string $status
     */
    protected $status;

    /**
     * The error code that is specific to the response
     * @var int $error_code
     */
    protected $error_code;

    /**
     * The array of data to be returned
     * @var array $data
     */
    protected $data;

    /**
     * The message the clarifies why the action is successful or failed
     * @var string $message
     */
    protected $message;

    /**
     * @param string $status
     * @param string $message
     * @param int $error_code
     * @param array $data
     * @return $this
     */
    public function __construct($status, $message, $error_code, $data=[])
    {
        $this->status = $status;
        $this->message = $message;
        $this->error_code = $error_code;
        $this->data = $data;
    }

    /**
     * Return array format of the class members
     * @return array
     */
    public function toArray() : array 
    {
        $result = [
            'status' => $this->status,
            'message' => $this->message,
            'error_code' => $this->error_code,
            'data' => $this->data,
        ];

        if (count($this->data) == 0) {
            unset($result['data']);
        }

        return $result;
    }
}