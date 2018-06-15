<?php
/**
 * Created by PhpStorm.
 * User: test
 * Date: 2015/5/13
 * Time: 15:48
 */

namespace Util;

use GuzzleHttp\Client;

class Http
{
    protected $url = '';
    protected $sleep_time, $interval, $now_num;
    protected $client = null;

    public function __construct($interval = PHP_INT_MAX, $sleep_time = 0)
    {
        $this->sleep_time = $sleep_time;
        $this->interval = $interval;
        $this->now_num;
        $this->client = new Client();
    }

    public function getUrl()
    {
        return $this->url;
    }

    private function to_url($path, $parameters = [])
    {
        $info = parse_url($path);
        $params = isset($info['query']) ? $info['query'] : "";
        parse_str($params, $output);
        $parm_str = http_build_query(array_merge($output, $parameters));
        return $this->clear_urlcan($path) . (empty($parm_str) ? "" : '?' . $parm_str);
    }

    private function clear_urlcan($url)
    {
        if (strpos($url, '?') !== false) {
            $url = substr($url, 0, strpos($url, '?'));
        }
        return $url;
    }

    protected function waitOrDo()
    {
        $this->now_num++;
        if ($this->now_num % $this->interval == 0) {
            sleep($this->sleep_time);
        }
    }

    public function get($url, $params = [], $headers = [], $timeout = 10)
    {
        $this->waitOrDo();
        $this->url = $this->to_url($url, $params);
        $options = [];
        if(!empty($headers)){
            $options['headers'] = $headers;
        }
        if(!empty($timeout)){
            $options['timeout'] = $timeout;
        }

        return $this->client->get($this->url, $options)->getBody()->getContents();

    }

    public function put($url, $data = [], $params = [], $headers = [], $timeout = 10)
    {

        $this->waitOrDo();
        $this->url = $this->to_url($url, $params);
        $options = [];
        if(!empty($headers)){
            $options['headers'] = $headers;
        }
        if(!empty($data)){
            $options['form_params'] = $data;
        }
        if(!empty($timeout)){
            $options['timeout'] = $timeout;
        }

        return $this->client->put($this->url, $options)->getBody()->getContents();
    }

    public function delete($url, $params = [], $headers = [], $timeout = 10)
    {
        $this->waitOrDo();
        $this->url = $this->to_url($url, $params);
        $options = [];
        if(!empty($headers)){
            $options['headers'] = $headers;
        }
        if(!empty($timeout)){
            $options['timeout'] = $timeout;
        }

        return $this->client->delete($this->url, $options)->getBody()->getContents();
    }

    public function post($url, $data = [], $params = [], $headers = [], $timeout = 10)
    {
        $this->waitOrDo();
        $this->url = $this->to_url($url, $params);
        $options = [];
        if(!empty($headers)){
            $options['headers'] = $headers;
        }
        if(!empty($data)){
            $options['form_params'] = $data;
        }
        if(!empty($timeout)){
            $options['timeout'] = $timeout;
        }

        return $this->client->post($this->url, $options)->getBody()->getContents();
    }

    function getJson($url, $params = [], $headers = [], $timeout = 10)
    {
        try {
            return json_decode($this->get($url, $params, $headers, $timeout), true);
        } catch (\Exception $e) {
            return null;
        }

    }

    function postJson($url, $data = [], $params = [], $headers = [], $timeout = 10)
    {
        try {
            return json_decode($this->post($url, $data, $params, $headers, $timeout), true);
        } catch (\Exception $e) {
            return null;
        }

    }
}