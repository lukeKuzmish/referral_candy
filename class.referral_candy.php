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

        
    } // __call
} // ReferralCandy

