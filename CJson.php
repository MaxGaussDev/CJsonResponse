<?php
/**
 * \brief   
 * \details     
 * @author  Mario Pastuović
 * @version 1.0
 * \date 28.04.16.
 * \copyright
 *     This code and information is provided "as is" without warranty of
 *     any kind, either expressed or implied, including but not limited to
 *     the implied warranties of merchantability and/or fitness for a
 *     particular purpose.
 *     \par
 *     Copyright (c) Gauss d.o.o. All rights reserved
 * Created by PhpStorm.
 */

namespace Cbundle\Response;

class CJson
{

    /**
     * Custom json response
     */

    public function sendJSONResponseWithData($data = null, $message = null, $code = 200){

        http_response_code($code);

        header('Content-type: application/json');

        header('Access-Control-Allow-Headers : origin, content-type, accept');
        header('Access-Control-Allow-Origin : *');
        header('Access-Control-Allow-Methods : POST, GET');

        if($data != null){
            header('ETag : '.md5((string)$data));
        }

        header('Powered-By : Gudy-kung-fu-scripts');

        $response_data = array(
            "code" => $code,
            "status" => $this->get_status_message($code)
        );

        if($message != null){
            $response_data['log_msg'] = $message;
        }

        if($data != null) {
            $response_data['content'] = $data;
        }

        echo json_encode($response_data);
    }

    public function sendOKResponseWithData($data){
        return $this->sendJSONResponseWithData($data, null, 200);
    }

    public function sendNotFoundResponse(){
        return $this->sendJSONResponseWithData(null, null, 404);
    }

    public function sendMissingParametersResponse(){
        return $this->sendJSONResponseWithData(null, null, 400);
    }

    public function sendUnauthorizedResponse(){
        return $this->sendJSONResponseWithData(null, null, 401);
    }

    public function sendForbiddenResponse(){
        return $this->sendJSONResponseWithData(null, null, 403);
    }

    /**
     * Function containing status messages
     */

    private function get_status_message($error){
        // Validate input
        if (!is_numeric($error)) return $this -> getMessage(500);

        $status = array(
            100 => 'Continue',
            101 => 'Switching Protocols',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            306 => 'Missing action',
            307 => 'Temporary Redirect',
            400 => 'Bad Request or Missing Parameters',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported',
            // Missing error
            666 => 'Missing Error Message',
            // Parameters
            702 => 'Missing Parameter',
            //Videos
            900 => 'Missing video URL'
        );

        if (array_key_exists($error, $status))
            return $status[$error];
        else
            return $this ->getMessage(666);
    }

    /**
     * Gets the message based off the set code
     */
    public function getMessage($code) {
        // Validate input
        if (!is_numeric($code)) return $this -> getMessage(500);

        // Validate error message if it exists, if not return 417
        $validateError = $this -> get_status_message($code);
        if (is_object($validateError)) return $validateError;

        // Generate the message object and return it
        $message = new \stdClass();
        $message -> status = 'Error';
        $message -> code = intval($code);
        $message -> message = $validateError;
        return $message;
    }

}