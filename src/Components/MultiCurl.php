<?php

namespace App\Components;

class MultiCurl
{
    /**
     * Current curl version.
     *
     * @var string
     */
    private $_curl_version;

    /**
     * max. number of simultaneous connections allowed.
     *
     * @var int
     */
    private $_maxConcurrent = 0;

    /**
     * shared cURL options.
     *
     * @var array
     */
    private $_options = [];

    /**
     * shared cURL request headers.
     *
     * @var array
     */
    private $_headers = [];

    /**
     * default callback.
     *
     * @var callable|null
     */
    private $_callback;

    /**
     * all requests must be completed by this time.
     *
     * @var int
     */
    private $_timeout = 20000;

    /**
     * request_queue.
     *
     * @var array
     */
    private $requests = [];

    /**
     * MultiCurl constructor.
     *
     * @param int $max_concurrent
     */
    public function __construct($max_concurrent = 10)
    {
        $this->setMaxConcurrent($max_concurrent);
        $this->_curl_version = curl_version()['version'];
    }

    public function setMaxConcurrent($max_requests)
    {
        if ($max_requests > 0) {
            $this->_maxConcurrent = $max_requests;
        }
    }

    public function setOptions(array $options)
    {
        $this->_options = $options;
    }

    public function setHeaders(array $headers)
    {
        if (is_array($headers) && count($headers)) {
            $this->_headers = $headers;
        }
    }

    public function setCallback(callable $callback)
    {
        $this->_callback = $callback;
    }

    public function setTimeout($timeout)
    {
        if ($timeout > 0) {
            $this->_timeout = $timeout;
        }
    }

    //Add a request to the request queue

    /**
     * Add a request to the request queue.
     *
     * @param string     $url
     * @param array|null $post_data
     * @param array|null $user_data
     *
     * @return int
     */
    public function addRequest(
        $url,
        $post_data = null,
        callable $callback = null,
        $user_data = null,
        array $options = null,
        array $headers = null
    ) {
        $this->requests[] = [
            'url' => $url,
            'post_data' => ($post_data) ? $post_data : null,
            'callback' => ($callback) ? $callback : $this->_callback,
            'user_data' => ($user_data) ? $user_data : null,
            'options' => ($options) ? $options : null,
            'headers' => ($headers) ? $headers : null,
        ];

        return count($this->requests) - 1;
    }

    /**
     * Reset request queue.
     */
    public function reset()
    {
        $this->requests = [];
    }

    /**
     * Process all requests in queue.
     */
    public function execute()
    {
        $requests_map = [];
        $multi_handle = curl_multi_init();
        $num_outstanding = 0;
        //start processing the initial request queue
        $num_initial_requests = min($this->_maxConcurrent, count($this->requests));
        for ($i = 0; $i < $num_initial_requests; ++$i) {
            $this->initRequest($i, $multi_handle, $requests_map);
            ++$num_outstanding;
        }
        do {
            do {
                $mh_status = curl_multi_exec($multi_handle, $active);
            } while (CURLM_CALL_MULTI_PERFORM == $mh_status);
            if (CURLM_OK != $mh_status) {
                break;
            }
            //a request is just completed, find out which one
            while ($completed = curl_multi_info_read($multi_handle)) {
                $this->processRequest($completed, $multi_handle, $requests_map);
                --$num_outstanding;
                //try to add/start a new requests to the request queue
                while (
                    $num_outstanding < $this->_maxConcurrent && //under the limit
                    $i < count($this->requests) && isset($this->requests[$i]) // requests left
                ) {
                    $this->initRequest($i, $multi_handle, $requests_map);
                    ++$num_outstanding;
                    ++$i;
                }
            }
        } while ($active || count($requests_map));
        $this->reset();
        curl_multi_close($multi_handle);
    }

    /**
     * @return array|mixed
     */
    private function buildOptions(array $request)
    {
        $url = $request['url'];
        $post_data = $request['post_data'];
        $individual_opts = $request['options'];
        $individual_headers = $request['headers'];
        $options = ($individual_opts) ? $individual_opts + $this->_options : $this->_options;
        $headers = ($individual_headers) ? $individual_headers + $this->_headers : $this->_headers;
        $options[CURLOPT_RETURNTRANSFER] = true;
        $options[CURLOPT_FOLLOWLOCATION] = true;
        $options[CURLOPT_NOSIGNAL] = 1;
        if (version_compare($this->_curl_version, '7.16.2') >= 0) {
            $options[CURLOPT_CONNECTTIMEOUT_MS] = $this->_timeout;
            $options[CURLOPT_TIMEOUT_MS] = $this->_timeout;
            unset($options[CURLOPT_CONNECTTIMEOUT]);
            unset($options[CURLOPT_TIMEOUT]);
        } else {
            $options[CURLOPT_CONNECTTIMEOUT] = round($this->_timeout / 1000);
            $options[CURLOPT_TIMEOUT] = round($this->_timeout / 1000);
            unset($options[CURLOPT_CONNECTTIMEOUT_MS]);
            unset($options[CURLOPT_TIMEOUT_MS]);
        }
        if ($url) {
            $options[CURLOPT_URL] = $url;
        }
        if ($headers) {
            $options[CURLOPT_HTTPHEADER] = $headers;
        }
        if (null !== $post_data) {
            $options[CURLOPT_POST] = 1;
            $options[CURLOPT_POSTFIELDS] = is_array($post_data) ? http_build_query($post_data) : $post_data;
        }

        return $options;
    }

    /**
     * @param int      $request_num
     * @param resource $multi_handle
     * @param array    $requests_map
     */
    private function initRequest($request_num, $multi_handle, &$requests_map)
    {
        $request = &$this->requests[$request_num];
        $this->addTimer($request);
        $ch = curl_init();
        $options = $this->buildOptions($request);
        $request['options_set'] = $options;
        curl_setopt_array($ch, $options);
        curl_multi_add_handle($multi_handle, $ch);
        $ch_hash = (string) $ch;
        $requests_map[$ch_hash] = $request_num;
    }

    /**
     * @param array    $completed
     * @param resource $multi_handle
     */
    private function processRequest($completed, $multi_handle, array &$requests_map)
    {
        $ch = $completed['handle'];
        $ch_hash = (string) $ch;
        $request = &$this->requests[$requests_map[$ch_hash]];
        $request_info = curl_getinfo($ch);
        $request_info['curle'] = $completed['result'];
        $request_info['handle'] = $ch;
        $request_info['time'] = $time = $this->stopTimer($request);
        $request_info['url_raw'] = $url = $request['url'];
        $request_info['user_data'] = $user_data = $request['user_data'];
        if (0 !== curl_errno($ch) || 200 !== (int) $request_info['http_code']) {
            $response = false;
        } else {
            $response = curl_multi_getcontent($ch);
        }
        $callback = $request['callback'];
        $options = $request['options_set'];
        if ($response && !empty($options[CURLOPT_HEADER])) {
            $k = (int) $request_info['header_size'];
            $request_info['response_header'] = substr($response, 0, $k);
            $response = substr($response, $k);
        }
        unset($requests_map[$ch_hash]);
        curl_multi_remove_handle($multi_handle, $ch);
        if ($callback) {
            $callback($response, $url, $request_info, $user_data, $time);
        }
        $request = null;
    }

    private function addTimer(array &$request): void
    {
        $request['timer'] = microtime(true);
        $request['time'] = false;
    }

    /**
     * @return mixed
     */
    private function stopTimer(array &$request)
    {
        $elapsed = $request['timer'] - microtime(true);
        $request['time'] = $elapsed;
        unset($request['timer']);

        return $elapsed;
    }
}
