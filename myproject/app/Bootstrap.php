<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/Plugins.php';
require_once __DIR__ . '/routes.php';

class Bootstrap
{
    public $request;
    public $path;

    public function __construct()
    {
        $this->set_header();
        $this->handle_request();
        $this->handle_plugins();
        $this->handle_routes();
    }

    private function set_header()
    {
        header("Content-Type: application/json; charset=UTF-8");
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, GET');
        header("Access-Control-Allow-Headers: X-Requested-With");
    }

    private function handle_request()
    {
        $data_json = json_decode(file_get_contents('php://input'), true);
        $data_post = $_POST;
        $data_get = $_GET;
        $this->request = array_merge($data_json ?? [], $data_post, $data_get);

        // $this->path = $_SERVER['PATH_INFO'] ?? '/';
        $this->path = explode('?', $_SERVER['REQUEST_URI'])[0];
    }

    private function handle_plugins()
    {
        foreach (PLUGINS as $plugin) {
            try {
                $c = PLUGINS_NAMESPACE . '\\' . $plugin;
                $n = new $c();
            } catch (\Throwable $e) {
                //here developer can send to telegram as notifiction

                response(false, 'Please contact developer for missing plugin.', [
                    'error' => $e->getCode()
                ]);
            }
        }
    }

    private function handle_routes()
    {
        if (!array_key_exists($this->path, ROUTES)) {
            return response(false, 'API is not valid.');
        }

        try {
            $module = ROUTES[$this->path]['module'] ?? false;
            if ($module) {
                return $this->call_module($module);
            }

            $nameSpace = '\\' . ROUTES[$this->path]['namespace'] . '\\' . ROUTES[$this->path]['class'];
            $action = ROUTES[$this->path]['action'];
            $this->handle_rules(ROUTES[$this->path]['params'] ?? []);
            $cls = new $nameSpace($this->request);
            $cls->$action();
        } catch (\Throwable $e) {
            //here developer can send to telegram as notifiction

            response(false, 'Please contact developer for missing routes.', [
                'error' => $e->getCode()
            ]);
        }
    }

    private function handle_rules($rules)
    {
        foreach ($rules as $param => $property) {
            if (!isset($this->request[$param])) {
                //required
                if (isset($property['required']) && $property['required'] == true) {
                    response(false, 'Missing required parameter: ' . $param);
                }
            } else {
                //type
                if (isset($property['type']) && $property['type'] != gettype($this->request[$param])) {
                    response(false, 'Invalid type for: ' . $param);
                }

                if (in_array(gettype($this->request[$param]), ['array', 'string'])) {
                    //minLength
                    if (isset($property['minLength'])) {
                        if (
                            (is_array($this->request[$param]) && count($this->request[$param]) < $property['minLength']) ||
                            (is_string($this->request[$param]) && strlen($this->request[$param]) < $property['minLength'])
                        ) {
                            response(false, 'Length is too short for : ' . $param);
                        }
                    }
                    //maxLength
                    if (isset($property['maxLength'])) {
                        if (
                            (is_array($this->request[$param]) && count($this->request[$param]) > $property['maxLength']) ||
                            (is_string($this->request[$param]) && strlen($this->request[$param]) > $property['maxLength'])
                        ) {
                            response(false, 'Length is too long for : ' . $param);
                        }
                    }
                    //exactLength
                    if (isset($property['exactLength'])) {
                        if (
                            (is_array($this->request[$param]) && count($this->request[$param]) != $property['exactLength']) ||
                            (is_string($this->request[$param]) && strlen($this->request[$param]) != $property['exactLength'])
                        ) {
                            response(false, 'Length is not match : ' . $param);
                        }
                    }
                }
            }
        }
    }

    private function call_module($module)
    {
        $ch = curl_init();
        $url = $module . ":80/v1/user/login";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->request);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        $response = curl_exec($ch);
        echo $response;
    }
}

//common functions 
function response(bool $status, string $message, array $data = [])
{
    echo json_encode([
        'status' => $status,
        'message' => $message == '' ? ($status ? 'Granted' : 'Denied') : $message,
        'data' => $data,
        'server' => getenv('SERVERNAME')
    ]);
    exit();
}

function p($d)
{
    print_r($d);
}
