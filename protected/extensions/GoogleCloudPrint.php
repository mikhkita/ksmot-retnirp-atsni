<?php

require_once 'HttpRequest.Class.php';

class GoogleCloudPrint {
	
	const PRINTERS_SEARCH_URL = "https://www.google.com/cloudprint/search";
	const PRINT_URL = "https://www.google.com/cloudprint/submit";
    const JOBS_URL = "https://www.google.com/cloudprint/jobs";
    const DELETE_URL = "https://www.google.com/cloudprint/deletejob";

	private $authtoken;
	private $httpRequest;
	private $refreshtoken;

	public $redirectConfig = array(
        'client_id'     => '219068004234-s44i7s2d2jgck7re70to362n21v6sbbd.apps.googleusercontent.com',
        'redirect_uri'  => 'http://hashtag.kitaev.me/oAuthRedirect.php',
        'response_type' => 'code',
        'scope'         => 'https://www.googleapis.com/auth/cloudprint',
    );
    
    public $authConfig = array(
        'code' => '',
        'client_id'     => '219068004234-s44i7s2d2jgck7re70to362n21v6sbbd.apps.googleusercontent.com',
        'client_secret' => 'v8r6Wweh_h_HAP-YKS12OPjz',
        'redirect_uri'  => 'http://hashtag.kitaev.me/oAuthRedirect.php',
        "grant_type"    => "authorization_code"
    );
    
    public $offlineAccessConfig = array(
        'access_type' => 'offline'
    );
    
    public $refreshTokenConfig = array(
        
        'refresh_token' => "",
        'client_id' => '219068004234-s44i7s2d2jgck7re70to362n21v6sbbd.apps.googleusercontent.com',
        'client_secret' => 'v8r6Wweh_h_HAP-YKS12OPjz',
        'grant_type' => "refresh_token" 
    );
    
    public $urlconfig = array(	
        'authorization_url' 	=> 'https://accounts.google.com/o/oauth2/auth',
        'accesstoken_url'   	=> 'https://accounts.google.com/o/oauth2/token',
        'refreshtoken_url'      => 'https://www.googleapis.com/oauth2/v3/token'
    );
	
	/**
	 * Function __construct
	 * Set private members varials to blank
	 */
	public function __construct() {
		
		$this->authtoken = "";
		$this->httpRequest = new HttpRequest();
	}
	
	/**
	 * Function setAuthToken
	 *
	 * Set auth tokem
	 * @param string $token token to set
	 */
	public function setAuthToken($token) {
		$this->authtoken = $token;
	}
	
	/**
	 * Function getAuthToken
	 *
	 * Get auth tokem
	 * return auth tokem
	 */
	public function getAuthToken() {
		return $this->authtoken;
	}
	
	
	/**
	 * Function getAccessTokenByRefreshToken
	 *
	 * Gets access token by making http request
	 * 
	 * @param $url url to post data to
	 * 
	 * @param $post_fields post fileds array
	 * 
	 * return access tokem
	 */
	
	public function getAccessTokenByRefreshToken($url,$post_fields) {
		$responseObj =  $this->getAccessToken($url,$post_fields);
		return $responseObj->access_token;
	}
	
	
	/**
	 * Function getAccessToken
	 *
	 * Makes Http request call
	 * 
	 * @param $url url to post data to
	 * 
	 * @param $post_fields post fileds array
	 * 
	 * return http response
	 */
	public function getAccessToken($url,$post_fields) {
		
		$this->httpRequest->setUrl($url);
		$this->httpRequest->setPostData($post_fields);
		$this->httpRequest->send();
		$response = json_decode($this->httpRequest->getResponse());
		return $response;
	}
	
	/**
	 * Function getPrinters
	 *
	 * Get all the printers added by user on Google Cloud Print. 
	 * Follow this link https://support.google.com/cloudprint/answer/1686197 in order to know how to add printers
	 * to Google Cloud Print service.
	 */
	public function getPrinters() {
		
		// Check if we have auth token
		if(empty($this->authtoken)) {
			// We don't have auth token so throw exception
			throw new Exception("Please first login to Google");
		}
		
		// Prepare auth headers with auth token
		$authheaders = array(
		"Authorization: Bearer " .$this->authtoken
		);
		
		$this->httpRequest->setUrl(self::PRINTERS_SEARCH_URL);
		$this->httpRequest->setHeaders($authheaders);
		$this->httpRequest->send();
		$responsedata = $this->httpRequest->getResponse();
		// Make Http call to get printers added by user to Google Cloud Print
		$printers = json_decode($responsedata);
		// Check if we have printers?
		if(is_null($printers)) {
			// We dont have printers so return balnk array
			return array();
		}
		else {
			// We have printers so returns printers as array
			return $this->parsePrinters($printers);
		}
		
	}
	
	/**
	 * Function sendPrintToPrinter
	 * 
	 * Sends document to the printer
	 * 
	 * @param Printer id $printerid    // Printer id returned by Google Cloud Print service
	 * 
	 * @param Job Title $printjobtitle // Title of the print Job e.g. Fincial reports 2012
	 * 
	 * @param File Path $filepath      // Path to the file to be send to Google Cloud Print
	 * 
	 * @param Content Type $contenttype // File content type e.g. application/pdf, image/png for pdf and images
	 */
	public function sendPrintToPrinter($printerid,$printjobtitle,$filepath,$contenttype) {
		
	// Check if we have auth token
		if(empty($this->authtoken)) {
			// We don't have auth token so throw exception
			throw new Exception("Please first login to Google by calling loginToGoogle function");
		}
		// Check if prtinter id is passed
		if(empty($printerid)) {
			// Printer id is not there so throw exception
			throw new Exception("Please provide printer ID");	
		}
		// Open the file which needs to be print
		$handle = fopen($filepath, "rb");
		if(!$handle)
		{
			// Can't locate file so throw exception
			throw new Exception("Could not read the file. Please check file path.");
		}
		// Read file content
		$contents = fread($handle, filesize($filepath));
		fclose($handle);

		$ticket = array(
			"version" => "1.0",
			"print" => array(
				"media_size" => array(
					"width_microns" => 100000, 
					"height_microns" => 148000, 
					"vendor_id" => "JPN_HAGAKI"
				),
				"margins" => array(
		      		"top_microns" => 3000,
		      		"right_microns" => 0,
		      		"bottom_microns" => 0,
		      		"left_microns" => 0
		      	),
		      	"dpi" => array(
		      		"vendor_id" => "psk:High",
		      		"vertical_dpi" => 360,
			        "horizontal_dpi" => 360
		      	)
			)
		);

		// return false;
		
		// Prepare post fields for sending print
		$post_fields = array(
				
			'printerid' => $printerid,
			'title' => $printjobtitle,
			'contentTransferEncoding' => 'base64',
			'content' => base64_encode($contents), // encode file content as base64
			'contentType' => $contenttype,
			'ticket' => json_encode($ticket)
		);

		echo "<br>".json_encode($ticket);

		// Prepare authorization headers
		$authheaders = array(
			"Authorization: Bearer " . $this->authtoken
		);
		
		// Make http call for sending print Job
		$this->httpRequest->setUrl(self::PRINT_URL);
		$this->httpRequest->setPostData($post_fields);
		$this->httpRequest->setHeaders($authheaders);
		$this->httpRequest->send();
		$response = json_decode($this->httpRequest->getResponse());
		
		// Has document been successfully sent?
		if($response->success=="1") {
			
			return array('status' =>true,'errorcode' =>'','errormessage'=>"", 'id' => $response->job->id);
		}
		else {
			
			return array('status' =>false,'errorcode' =>$response->errorCode,'errormessage'=>$response->message);
		}
	}

    public function deleteJobs($event_id)
    {
        // Prepare auth headers with auth token
        $authheaders = array(
            "Authorization: Bearer " .$this->authtoken
        );

        // Make http call for sending print Job
        $this->httpRequest->setUrl(self::JOBS_URL);
        $this->httpRequest->setHeaders($authheaders);
        $this->httpRequest->setPostData(array("status" => "QUEUED"));
        $this->httpRequest->send();
        $responsedata = json_decode($this->httpRequest->getResponse());

        if( isset($responsedata->jobs) )
        foreach ($responsedata->jobs as $job){
    		$arr = explode(" ", $job->title);
    		if( intval($arr[0]) == intval($event_id) ){
    			$post_fields = array("jobid" => $job->id);
	        	$this->httpRequest->setUrl(self::DELETE_URL);
		        $this->httpRequest->setHeaders($authheaders);
		        $this->httpRequest->setPostData(array("jobid" => $job->id));
		        $this->httpRequest->send();
    		}
        }

        return 'UNKNOWN';
    }

    public function deleteJobsByApi($api_key){
    	// Prepare auth headers with auth token
        $authheaders = array(
            "Authorization: Bearer " .$this->authtoken
        );

        // Make http call for sending print Job
        $this->httpRequest->setUrl(self::JOBS_URL);
        $this->httpRequest->setHeaders($authheaders);
        $this->httpRequest->setPostData(array("status" => "QUEUED","printerid" => $api_key));
        $this->httpRequest->send();
        $responsedata = json_decode($this->httpRequest->getResponse());

        foreach ($responsedata->jobs as $job){
			$post_fields = array("jobid" => $job->id);
        	$this->httpRequest->setUrl(self::DELETE_URL);
	        $this->httpRequest->setHeaders($authheaders);
	        $this->httpRequest->setPostData(array("jobid" => $job->id));
	        $this->httpRequest->send();
        }

        return 'UNKNOWN';
    }

    public function getQueueCount(){
    	// Prepare auth headers with auth token
        $authheaders = array(
            "Authorization: Bearer " .$this->authtoken
        );

        // Make http call for sending print Job
        $this->httpRequest->setUrl(self::JOBS_URL);
        $this->httpRequest->setHeaders($authheaders);
        $this->httpRequest->setPostData(array("status" => "QUEUED"));
        $this->httpRequest->send();
        $responsedata = json_decode($this->httpRequest->getResponse());

        $result = array();

        if( isset($responsedata->jobs) )
        foreach ($responsedata->jobs as $job){
        	if( !isset($result[$job->printerid]) ){
        		$result[$job->printerid] = 0;
        	}
        	$result[$job->printerid]++;
        }

        return $result;
    }


	/**
	 * Function parsePrinters
	 * 
	 * Parse json response and return printers array
	 * 
	 * @param $jsonobj // Json response object
	 * 
	 */
	private function parsePrinters($jsonobj) {
		
		$printers = array();
		if (isset($jsonobj->printers)) {
			foreach ($jsonobj->printers as $gcpprinter) {
				$printers[] = array('id' =>$gcpprinter->id,'name' =>$gcpprinter->name,'displayName' =>$gcpprinter->displayName,
						    'ownerName' => $gcpprinter->ownerName,'connectionStatus' => $gcpprinter->connectionStatus,
						    );
			}
		}
		return $printers;
	}
}
