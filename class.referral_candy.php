<?php

class ReferralCandy {

    public static       $DEFAULT_API_URL = 'https://my.referralcandy.com/api/v1/';
    protected static    $API_METHODS = array(
                            'get'   =>  array('verify', 'referrals', 'referrer', 'contacts'),
                            'post'  =>  array('purchase', 'referral', 'signup', 'invite'),
                        );
    
    protected           $access_id;
    protected           $access_key;

    public function __construct($access_id = null, $access_key = null) {
        if (($access_id == null) or ($access_key == null)) {
            throw new Exception('access_id nor access_key can be null!');
        }

        $this->access_id = $access_id;
        $this->access_key = $access_key;

    } // __construct

    public function __call($method, $args) {
        
        $methodFound = false;

        foreach(self::$API_METHODS as $verb => $apiMethodNames) {
            if (in_array($method, $apiMethodNames)) {
                $methodFound = true;
                break;
            }
        }
        
        if (!$methodFound) {
            throw new Exception('No such ReferralCandy API method exists!');
        }
        
        $url = self::$DEFAULT_API_URL . $method . '.json';
        
        $apiParameters = array();
        if (isset($args[0])) {
            $apiParameters = $args[0];
        }
        
        $apiParameters['accessID'] =  $this->access_id;
        $apiParameters['timestamp'] = time();
        
        $apiParameters = $this->addSignatureTo($apiParameters);
        
        return self::callEndpoint($url, $verb, $apiParameters);
        

        
    } // __call
    
    private static function callEndpoint($url, $verb, $params) {
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if (strtolower($verb) == 'get') {
            curl_setopt($ch, CURLOPT_HTTPGET, true);
        }
        else {
            curl_setopt($ch, CURLOPT_POST, true);
        }
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        $chData = curl_exec($ch);
        curl_close($ch);
        return $chData;
    }
    
    private function addSignatureTo($apiParameters) {
        
        $collected = array();
        foreach($apiParameters as $key => $val) {
            $collected[] = $key . '=' . $val;
        }
        asort($collected);
        $collectedStr = implode('', $collected);
        $collectedStr = $this->access_key . $collectedStr;
        $apiParameters['signature'] = md5($collectedStr);
        return $apiParameters;
    }
    
    private function generateSignature($apiParameters) {
        
    }
} // ReferralCandy

