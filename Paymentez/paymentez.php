<?php

class Paymentez
{
    /**
     * @version "1.0.0";
     */
    const VERSION = '1.0.0';
    /*
     * @var $API_ROOT_URL is a main URL to Acces
     */

    protected $merchant_id;
    protected $cliente_app_code;
    protected $cliente_app_key;
    protected $env_mode;
    protected $test_mode = true;

    /**
     * Configuration for CURL.
     */
    public $CURL_OPTS = array(
        CURLOPT_USERAGENT => 'Paymentez-PHP-SDK-1.0.0',
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_CONNECTTIMEOUT => 30,
        CURLOPT_TIMEOUT => 60,
    );

    protected static $SERVER_STG_URL = 'https://ccapi-stg.paymentez.com';
    protected static $SERVER_PROD_URL = 'https://ccapi.paymentez.com';

    public function __construct($cliente_app_code, $cliente_app_key, $merchant_id, $env_mode = '', $test_mode = true)
    {
        $this->cliente_app_code = $cliente_app_code;
        $this->cliente_app_key = $cliente_app_key;
        $this->merchant_id = $merchant_id;
        $this->env_mode = $env_mode;
    }

    public function getUniqToken($auth_timestamp, $cliente_app_key)
    {
        $uniq_token_string = $cliente_app_key.$auth_timestamp;

        return hash('sha256', $uniq_token_string);
    }

    public function getAuthToken($cliente_app_code, $cliente_app_key)
    {
        $fecha_actual = time();
        $auth_timestamp = (string) ($fecha_actual);
        $unique_token = $this->getUniqToken($auth_timestamp, $cliente_app_key);

        $string_auth_token = $cliente_app_code.';'.$auth_timestamp.';'.$unique_token;

        $auth_token = base64_encode($cliente_app_code.';'.$auth_timestamp.';'.$unique_token);

        return $auth_token;
    }

    public function getServerURL()
    {
        $SERVER_URL = $this->SERVER_STG_URL;

        if ($this->env_mode == '') {
            if ($this->test_mode) {
                $SERVER_URL = self::$SERVER_STG_URL;
            } else {
                $SERVER_URL = self::$SERVER_PROD_URL;
            }
        } else {
            if ($this->env_mode == 'stg') {
                $SERVER_URL = self::$SERVER_STG_URL;
            } elseif ($this->env_mode == 'prod') {
                $SERVER_URL = self::$SERVER_PROD_URL;
            }
        }

        return $SERVER_URL;
    }

    /**
     * Execute a GET Request.
     *
     * @param string $path
     * @param array  $params
     * @param bool   $assoc
     *
     * @return mixed
     */
    public function get($path, $params = null, $assoc = false)
    {
        $auth = $this->getAuthToken($this->cliente_app_code, $this->cliente_app_key);
        $auth_token = 'Auth-Token:'.$auth;

        $opts = array(
            CURLOPT_HTTPHEADER => array('Content-Type: application/json', $auth_token),
        );

        $exec = $this->execute($path, $opts, $params, $assoc);

        return $exec;
    }

    /**
     * Execute a POST Request.
     *
     * @param string $body
     * @param array  $params
     *
     * @return mixed
     */
    public function post($path, $body = null, $params = array())
    {
        $auth = $this->getAuthToken($this->cliente_app_code, $this->cliente_app_key);
        $auth_token = 'Auth-Token:'.$auth;

        $body = json_encode($body);
        $opts = array(
            CURLOPT_HTTPHEADER => array('Content-Type: application/json', $auth_token),
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $body,
        );

        $exec = $this->execute($path, $opts, $params);

        return $exec;
    }

    /**
     * Execute a PUT Request.
     *
     * @param string $path
     * @param string $body
     * @param array  $params
     *
     * @return mixed
     */
    public function put($path, $body = null, $params = array())
    {
        $auth = $this->getAuthToken($this->cliente_app_code, $this->cliente_app_key);
        $auth_token = 'Auth-Token:'.$auth;

        $body = json_encode($body);
        $opts = array(
            CURLOPT_HTTPHEADER => array('Content-Type: application/json', $auth_token),
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => $body,
        );

        $exec = $this->execute($path, $opts, $params);

        return $exec;
    }

    /**
     * Execute a DELETE Request.
     *
     * @param string $path
     * @param array  $params
     *
     * @return mixed
     */
    public function delete($path, $params)
    {
        $opts = array(
            CURLOPT_CUSTOMREQUEST => 'DELETE',
        );

        $exec = $this->execute($path, $opts, $params);

        return $exec;
    }

    /**
     * Execute a OPTION Request.
     *
     * @param string $path
     * @param array  $params
     *
     * @return mixed
     */
    public function options($path, $params = null)
    {
        $opts = array(
            CURLOPT_CUSTOMREQUEST => 'OPTIONS',
        );

        $exec = $this->execute($path, $opts, $params);

        return $exec;
    }

    /**
     * Execute all requests and returns the json body and headers.
     *
     * @param string $path
     * @param array  $opts
     * @param array  $params
     * @param bool   $assoc
     *
     * @return mixed
     */
    public function execute($path, $opts = array(), $params = array(), $assoc = false)
    {
        $uri = $this->make_path($path, $params);

        $ch = curl_init($uri);
        curl_setopt_array($ch, $this->CURL_OPTS);

        if (!empty($opts)) {
            curl_setopt_array($ch, $opts);
        }

        $response = curl_exec($ch);

        $return['body'] = json_decode($response, true);
        $return['httpCode'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        return $return;
    }

    /**
     * Check and construct an real URL to make request.
     *
     * @param string $path
     * @param array  $params
     *
     * @return string
     */
    public function make_path($path, $params = array())
    {
        if (!preg_match("/^\//", $path)) {
            $path = '/'.$path;
        }

        $uri = $this->getServerURL().$path;

        if (!empty($params)) {
            $paramsJoined = array();

            foreach ($params as $param => $value) {
                $paramsJoined[] = "$param=$value";
            }
            $params = '?'.implode('&', $paramsJoined);
            $uri = $uri.$params;
        }

        return $uri;
    }
}
