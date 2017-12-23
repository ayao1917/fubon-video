<?php

    $AgentID="";
    $AgentPwd="";
    $ValidateCode="";
    $ClientType="";
class sso {
    private $TokenId, $SysCode, $Key;
    private $serviceURL = 'https://m.fbl.com.tw:1114/Mobile/web/service/loginaction.asmx?WSDL';

    private $serviceURI='http://tempuri.org/SalesmanLogin';
    private $ValidateCode;
    private $AgentID;
    private $AgentPwd;
    private $ClientType;
    private $Cookie_NET_SessionId;
    
    function __construct() {
    } 

    public function setup($user, $pass, $validateCode, $session_id, $clientType='VIDEOWEB') {
        $ini = ini_set("soap.wsdl_cache_enabled","0");
        $this->AgentID = $user;
	$this->AgentPwd = $pass;
	$this->ValidateCode = $validateCode;
	$this->ClientType = $clientType;
        $this->Cookie_NET_SessionId = $session_id;

        global $AgentID;
        global $AgentPwd;
        global $ValidateCode;
        global $ClientType;
        $AgentID=$user;
        $AgentPwd=$pass;
        $ValidateCode=$validateCode;
        $ClientType=$clientType;
    }

    private function doLogin() {

        $options = array( 
	    'uri'=>$this->serviceURI,
	    'location'=>$this->serviceURL,
	    'trace'=>false,
            'cache_wsdl'=>WSDL_CACHE_NONE
        ); 

        $params = array($this->AgentID, $this->AgentPwd, $this->ValidateCode, $this->ClientType);
	$param = array( 
            "AgentID" => $this->AgentID, 
            "AgentPwd" => $this->AgentPwd, 
            "ValidateCode" => $this->ValidateCode, 
            "ClientType" => $this->ClientType 
	);
file_put_contents("/tmp/ff1", print_r($param, TRUE));

        //$client = new MySoapClient($this->serviceURL, array("soap_version"=> SOAP_1_2, "trace"=> 1, "exceptions" => 0));
        $client = new MySoapClient($this->serviceURL, array("trace"=> 1, "exceptions" => 0));

$client->__setCookie("ASP.NET_SessionId", $this->NET_SessionId);

//The following two seems optional
$client->__setCookie("CheckCode", $this->CheckCode);
$client->__setCookie("SessionID", $this->NET_SessionId);

	$result = $client->Login($param);


/*
        $result = $client->__soapCall("Login", 
                                        array('parameters'=>$param), 
                                        null, 
                                        new SoapHeader('http://tempuri.org/, 'SecurityToken', array('Key' => $this->Key))
                                        );
*/



	return $result;
    }
   
    public function login() {
        $result = $this->doLogin();

        file_put_contents("/tmp/fff", print_r($result, TRUE));

/*
//	return $result->GetDataResult;

	list($code, $msg) = explode('|', $result->GetDataResult);
	if ($code=='00') {
            list($id, $name, $region, $system, $unitcode, $unitname, $rank, $rest) = explode('-', $msg);
	    return "$code|$id|$name|$rank";
	} else {
	    return "$code|$code|$msg";
	}


	

	if ($code == '00') {
//            echo 'get user info successfully<br/>';


	} else {
        //    echo 'Get user info failed. Code='.$code.'. Msg='.$msg;
	}
*/
    }
}


class MySoapClient extends SoapClient {
    public function __construct($wsdl, $options) {
        $url = parse_url($wsdl);
        if ($url['port']) {
            $this->_port = $url['port'];
        }
        return parent::__construct($wsdl, $options);
    }
 
    public function __doRequest($request, $location, $action, $version) {
        global $AgentID;
        global $AgentPwd;
        global $ValidateCode;
        global $ClientType;

$request="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<env:Envelope xmlns:env=\"http://www.w3.org/2003/05/soap-envelope\" xmlns:ns1=\"http://tempuri.org/\"><env:Body><ns1:SalesmanLogin><ns1:AgentID>".$AgentID. "</ns1:AgentID><ns1:AgentPwd>".$AgentPwd. "</ns1:AgentPwd><ns1:ValidateCode>". $ValidateCode . "</ns1:ValidateCode><ns1:ClientType>".$ClientType . "</ns1:ClientType></ns1:SalesmanLogin></env:Body></env:Envelope>";

$action = "http://tempuri.org/SalesmanLogin";

        $parts = parse_url($location);
        if ($this->_port) {
            $parts['port'] = $this->_port;
        }
        $location = $this->buildLocation($parts);

file_put_contents("/tmp/ff3", print_r($request, TRUE));
file_put_contents("/tmp/ff4", print_r($location, TRUE));
file_put_contents("/tmp/ff5", print_r($action, TRUE));
file_put_contents("/tmp/ff6", print_r($version, TRUE));
 
        $return = parent::__doRequest($request, $location, $action, $version);
        return $return;
    }
 
    public function buildLocation($parts = array()) {
        $location = '';
 
        if (isset($parts['scheme'])) {
            $location .= $parts['scheme'].'://';
        }
        if (isset($parts['user']) || isset($parts['pass'])) {
            $location .= $parts['user'].':'.$parts['pass'].'@';
        }
        $location .= $parts['host'];
        if (isset($parts['port'])) {
            $location .= ':'.$parts['port'];
        }
        $location .= $parts['path'];
        if (isset($parts['query'])) {
            $location .= '?'.$parts['query'];
        }
 
        return $location;
    }
}

?>
