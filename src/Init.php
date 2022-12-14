<?php

    namespace KSeven\CPanel;

    class Init {
        
        /**
         * @var array 
         */
        protected $Auth;

        function __construct($Auth = [])
        {
            $this->Auth = $Auth;
        }

        /**
         *  Get userame used a api
         *  @param string
         */
        public function getUsername()
        {
            return $this->Auth["USER"];
        }

        /**
         *  Send API via cURL
         *  @param $endpoint
         *  @param array $params
         */
        public function sendRequest($endpoint, array $params = [])
        {
            $URL = sprintf("https://" . "%s" . ":" . "%s" . "/execute" . "/%s", $this->Auth["HOST"], $this->Auth["PORT"], $endpoint);
            if($params):
                $queryParams = http_build_query($params);
                $URL = $URL . "?" . $queryParams;
            endif;
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,0);
            curl_setopt($curl, CURLOPT_HEADER,0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
            $header[0] = "Authorization: Basic " . base64_encode($this->Auth["USER"] . ":" . $this->Auth["PASSWORD"]) . "\n\r";
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
            curl_setopt($curl, CURLOPT_URL, $URL);
            $result = curl_exec($curl);
            if (!$result):
                error_log("curl_exec threw error \"" . curl_error($curl) . "\" for $URL");   
            endif;
            curl_close($curl);
            return $result;
        }
    }