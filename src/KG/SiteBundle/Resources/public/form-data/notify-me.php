<?php
/**************************************************************************
 * MAIL UTILITY PHP SCRIPT
 * @author : R.Genesis.Art
 * This file is licensed to R.Genesis.Art (http://themeforest.net/user/r_genesis) and prohibited to copy or reuse it.
 * Copyright R.Genesis.Art 2015
**************************************************************************/
require('php_wrappers/MailChimp.php');
require('php_wrappers/CMBase.php');
require('php_wrappers/GetResponseAPI.class.php');
require('php_wrappers/aweber_api/aweber_api.php');
require('php_wrappers/iContactApi.php');
require('php_wrappers/constantcontact/src/Ctct/autoload.php');
require('config.php');

if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["email"])) {

	if (isset($_POST["email"]["val"]) && is_array($_POST["email"])) {
		$email = $_POST["email"]["val"];
	} else {
		$email = $_POST["email"];
	}
	$firstname = isset($_POST["name"]["val"]) ? $_POST["name"]["val"] : '';
	
	header('HTTP/1.1 200 OK');
	header('Status: 200 OK');
	header('Content-type: application/json');

	// Checking if the email writing is good
	if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
		
		/* The part for the storage in a .txt
		++++++++++++++++++++++++++++++++++++++++++++++*/
		if ($STORE_MODE == "file") {
			
			// SUCCESS SENDING
			if(@file_put_contents($STORE_FILE, strtolower($email)."\r\n", FILE_APPEND)) {
				echo json_encode(array(
					"status" => "success"
				));
			// ERROR SENDING
			} else {
				echo json_encode(array(
					"status" => "error",
					"type" => "FileAccessError"
				));
			}
		}

		/* MAILCHIMP
		++++++++++++++++++++++++++++++++++++++++++++++*/
		elseif ($STORE_MODE == "mailchimp") {
			
			$MailChimp = new \Drewm\MailChimp($MC_API_KEY);
			$result = $MailChimp->call('lists/subscribe', array(
						'id'                => $MC_LIST_ID,
						'email'             => array('email'=>$email, 'name'=>$firstname),
						'double_optin'      => false,
						'update_existing'   => true,
						'replace_interests' => false,
						'send_welcome'      => true,
					));     
	
			// SUCCESS SENDING
			if(isset($result["email"])) {
				if ($result["email"] == $email) {
					echo json_encode(array(
						"status" => "success"
					));
				}else{
					echo json_encode(array(
						"status" => "error",
						"type"   => "Looks like something went wrong. Please try again later."
					));
					errorlog("mailchimp", $result["name"]);
				}
			// ERROR SENDING
			} else {
				/*echo json_encode(array(
					"status" => "error",
					"type" => $result["name"]
				));*/
				echo json_encode(array(
					"status" => "error",
					"type"   => "Looks like something went wrong. Please try again later."
				));
				errorlog("mailchimp", $result["name"]);
			}
		}

		/* CAMPAIGN MONITOR
		++++++++++++++++++++++++++++++++++++++++++++++*/
		elseif ($STORE_MODE == "campaignmonitor") {
			$api_key     = $CM_API_KEY;
			$list_id     = $CM_LIST_ID;
			/*$client_id = null;
			$campaign_id = null;*/
			$cm          = new CampaignMonitor($api_key, null, null, $list_id);
			$result      = $cm->subscriberAdd($email, $firstname);
			
			// SUCCESS SENDING
			if($result['Result']['Code'] == 0) {     	
				echo json_encode(array(
					"status" => "success"
				));
				
			// ERROR SENDING
			} else {
				/*echo json_encode(array(
					"status" => "error",
					"type" => $result['Result']['Message']
				));*/
				echo json_encode(array(
					"status" => "error",
					"type"   => "Looks like something went wrong. Please try again later."
				));
				errorlog("campaignmonitor", "Error : ". $result['Result']['Code']." : ".$result['Result']['Message']);
			}
		}

		/* GET RESPONSE
		++++++++++++++++++++++++++++++++++++++++++++++*/
		elseif ($STORE_MODE == "getresponse") {
			$gr       = new GetResponse($GR_API_KEY);
			$campaign = $gr->getCampaignByName($GR_CAMPAIGN_NAME);
			$result   = $gr->addContact($campaign, $firstname, $email, 'standard', 0, array());

			// SUCCESS SENDING
			if(isset($result->queued) && $result->queued == 1) {
				echo json_encode(array(
					"status" => "success"
				));
				
			// ERROR SENDING
			} else {
				/*echo json_encode(array(
					"status" => "error",
					"type" => $result->message
				));*/
				echo json_encode(array(
					"status" => "error",
					"type"   => "Looks like something went wrong. Please try again later."
				));
				errorlog("getresponse", $result->message);
			}
		}

		/* AWEBER
		++++++++++++++++++++++++++++++++++++++++++++++*/
		elseif ($STORE_MODE == "aweber") {
			$auth_f = substr($AW_AUTH_CODE, 0, 8).'_auth.rgen';
			if (!file_exists($auth_f)) {
				try {
					$authorization_code = $AW_AUTH_CODE;
					$auth = AWeberAPI::getDataFromAweberID($authorization_code);
					list($consumerKey, $consumerSecret, $accessKey, $accessSecret) = $auth;
					file_put_contents($auth_f, json_encode($auth));
				}
				catch(AWeberAPIException $exc) {
					echo json_encode(array(
						"status" => "error",
						"type"   => "Looks like something went wrong. Please try again later."
					));
					errorlog("aweber", $exc->message);
				}
			}else{
				$auth_f_data = file_get_contents($auth_f);
				list($consumerKey, $consumerSecret, $accessToken, $accessSecret) = json_decode($auth_f_data);
				$aweber = new AWeberAPI($consumerKey, $consumerSecret);
				
				try {
					$account   = $aweber->getAccount($accessToken, $accessSecret);
					$list_data = $account->lists->find(array('name' => $AW_LIST_NAME));
					
					if (isset($list_data[0])) {
						$list = $list_data[0];
						
						$params = array(
							'email' => $email,
							'name'  => $firstname
						);
						
						$subscribers = $list->subscribers->create($params);

						# success!
						echo json_encode(array(
							"status" => "success"
						));	
					} else {
						echo json_encode(array(
							"status" => "error",
							"type"   => "List name not found."
						));
					}
				} catch(AWeberAPIException $exc) {
					echo json_encode(array(
						"status" => "error",
						"type"   => "Looks like something went wrong. Please try again later."
					));
					errorlog("aweber", $exc->message);
				}
			}
		}


		/* ICONTACT
		++++++++++++++++++++++++++++++++++++++++++++++*/
		elseif ($STORE_MODE == "icontact") {
			// Give the API your information
			iContactApi::getInstance()->setConfig(array(
				'appId'       => $IC_APP_ID, 
				'apiPassword' => $IC_API_PWD, 
				'apiUsername' => $IC_API_USER
			));

			$oiContact = iContactApi::getInstance();

			// Try to make the call(s)
			try {
				$lists = $oiContact->getLists();
				$list_id = null;
				if (isset($lists) && is_array($lists)) {
					foreach ($lists as $key => $value) {
						if ($value->name == $IC_LIST_NAME) {
							$list_id = $value->listId;
						} else {
							$list_id = null;
						}
					}
					if ($list_id != null) {
						// Create a contact
						$result = $oiContact->addContact($email, null, null, $firstname, null, '', '', '', '', '', '', '', null);
						// Subscribe contact to list - subscribeContactToList(contactId, listId, status)
						if (isset($result->contactId)) {
							$subscribe = $oiContact->subscribeContactToList($result->contactId, $list_id, 'normal');
							echo json_encode(array(
								"status" => "success"
							));
						}
					} else {
						echo json_encode(array(
							"status" => "error",
							"type"   => "Looks like something went wrong. Please try again later."
						));
						errorlog("icontact", "List ID not found.");
					}
				}else{
					echo json_encode(array(
						"status" => "error",
						"type"   => "Looks like something went wrong. Please try again later."
					));
					errorlog("icontact", "Lists data not found.");
				}
			} catch (Exception $oException) { // Catch any exceptions
				echo json_encode(array(
					"status" => "error",
					"type"   => "Looks like something went wrong. Please try again later."
				));
				$oiErrors = $oiContact->getErrors();
				foreach ($oiErrors as $key => $value) {
					errorlog("icontact", $value);
				}
				
			}
		}

		/* CONSTANT CONTACT
		++++++++++++++++++++++++++++++++++++++++++++++*/
		elseif ($STORE_MODE == "constantcontact") {
			
			$list_name = $CC_LIST_NAME;
			$list_id   = null;
			$cc        = new Ctct\ConstantContact($CC_API_KEY);
			try {
				try {
					$lists = $cc->getLists($CC_ACCESS_TOKEN);
					if (is_array($lists) && sizeof($lists) > 0) {
						foreach ($lists as $key => $value) {
							if (isset($value->name) && $value->name == $list_name) {
								$list_id = $value->id;
							}
						}
					}
					if (isset($email) && strlen($email) > 1 && $list_id != null) {
						$action = "Getting Contact By Email Address";
						try {
							// check to see if a contact with the email addess already exists in the account
							$response = $cc->getContactByEmail($CC_ACCESS_TOKEN, $email);
							// create a new contact if one does not exist
							if (empty($response->results)) {
								$action = "Creating Contact";
								$contact = new Ctct\Components\Contacts\Contact();
								$contact->addEmail($email);
								$contact->addList($list_id);
								$contact->first_name = $firstname;
								$returnContact = $cc->addContact($CC_ACCESS_TOKEN, $contact, true);

								# success!
								echo json_encode(array(
									"status" => "success"
								));
							}else{
								echo json_encode(array(
									"status" => "error",
									"type"   => "Email address already exists."
								));
							}
						} catch (CtctException $ex) {
							echo json_encode(array(
								"status" => "error",
								"type"   => "Looks like something went wrong. Please try again later."
							));
							errorlog("constantcontact", $ex->getErrors());
						}
					} else {
						echo json_encode(array(
							"status" => "error",
							"type"   => "Invalid list ID or email."
						));
						errorlog("constantcontact", $ex->getErrors());
					}
				} catch (Ctct\Exceptions\CtctException $ex) {
					foreach ($ex->getErrors() as $error) {
						echo json_encode(array(
							"status" => "error",
							"type"   => "Looks like something went wrong. Please try again later."
						));
						foreach ($ex->getErrors() as $error) {
							errorlog("constantcontact", $error);
						}
					}
				}
				
			} catch (Ctct\Exceptions\CtctException $ex) {
				echo json_encode(array(
					"status" => "error",
					"type"   => "Looks like something went wrong. Please try again later."
				));
				foreach ($ex->getErrors() as $error) {
					errorlog("constantcontact", $error);
				}
			}
		}

		/* ERROR
		++++++++++++++++++++++++++++++++++++++++++++++*/
		else {
			echo json_encode(array(
				"status" => "error",
				"type" => "Please select email storage type."
			));
		}
	// ERROR DURING THE VALIDATION 
	} else {
		echo json_encode(array(
			"status" => "error",
			"type" => "ValidationError"
		));
	}
} else {
	header('HTTP/1.1 403 Forbidden');
	header('Status: 403 Forbidden');
}

function errorlog($app, $details){

	$date = date("Y-m-d H:i:s");
	if (is_array($details)) {
		$info = '';
		foreach ($details as $key => $value) {
			$info .= $value."\n";
		}
	}else {
		$info = $details;
	}
	file_put_contents(
		"errorlog.txt", 
		$date." [".$app."]"."\n".
		$info."\n".
		"+++++++++++++++++++++++"."\n",
		FILE_APPEND
		);
}

?>