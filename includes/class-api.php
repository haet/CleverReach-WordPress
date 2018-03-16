<?php

class haet_cleverreach_api {
	
	 protected $api_key;

	 function __construct($api_key){
	 	$this->api_key = $api_key;
	 }


	/**
	 * Test API Key (valid / write-access)
	 *
	 * @return array
	 */
	public function test_api_key(){
		$test_result=array();
		$api = new SoapClient(HAET_CLEVERREACH_API_URL);
		try{
			$result = $api->groupGetList($this->api_key);
			if($result->status != "SUCCESS"){
				$test_result['success']=false;
				$test_result['message']=__( 'Your API key is invalid.', 'cleverreach' );
			}else{
				$test_group_name = 'WordPress-Cleverreach-Write-Access-Test';
				$result = $api->groupAdd($this->api_key, $test_group_name);
				if($result->status=='ERROR'){
					$test_result['success']=false;
					$test_result['message']=__( 'Your API has read-only permissions, please change to read-write-access.', 'cleverreach' );
				}else{
					$test_result['success']=true;
					$test_result['message']=__( 'Successfully connected to Cleverreach with your API key.', 'cleverreach' );
					$api->groupDelete($this->api_key, $result->data->id);
				}
			}
		} catch(Exception $e){
			$test_result['success']=false;
			$test_result['message']=__( 'Your API key is invalid.', 'cleverreach' );
		}
		return $test_result;
	}





	/**
	 * Retreive all lists from cleverreach API
	 *
	 * @return array
	 */
	public function get_lists(){

		$api = new SoapClient(HAET_CLEVERREACH_API_URL);
		try{
			$result = $api->groupGetList($this->api_key);
			if($result->status=="SUCCESS"){
				if( !is_array($result->data) ){
					$return['success']=false;
					$return['message']=__( 'You don\'t have any Cleverreach forms.', 'cleverreach' );
				}else{
					$return['success']=true;
					$return['lists']=array();
					foreach( $result->data AS $cr_list ){
						$forms_result = $api->formsGetList($this->api_key,$cr_list->id);
						$cr_list->forms = $forms_result->data;
						$return['lists'][] = $cr_list;
					}
				}
			}else{
				$return['success']=false;
				$return['message']=$result->message;
			}
		} catch(Exception $e){
			$return['success']=false;
			$return['message']=__( 'Could not connect to the Cleverreach API.', 'cleverreach' );
		}
		return $return;
	}




	/**
	 * Retreive list attributes from cleverreach API
	 *
	 * @return array
	 */
	public function get_list_attributes($list_id){
		$api = new SoapClient(HAET_CLEVERREACH_API_URL);
		try{
			$result = $api->groupGetDetails($this->api_key, $list_id);
			if($result->status=="SUCCESS"){
				$return['success']=true;
				$return['attributes'] = array_merge($result->data->globalAttributes, $result->data->attributes);
			}else{
				$return['success']=false;
				$return['message']=$result->message;
			}
		} catch(Exception $e){
			$return['success']=false;
			$return['message']=__( 'Could not connect to the Cleverreach API.', 'cleverreach' );
		}
		return $return;
	}



	/**
	 * Subscribe user
	 *
	 * @return array
	 */
	public function subscribe_user($settings, $submission, $form_id, $list_id, $source=null, $send_activation_mail=true ){
		$return=array();
		$email = $submission['cleverreach_email']->value;
		if(!$list_id || !$email || !$form_id){
			$return['success']=false;
			return $return;
		}

		$user_data = array();
		foreach ($submission as $key => $data) {
			if( !in_array($key, array('cleverreach_email') ) ){
				$user_data[] = array(
					'key'	=>	$key,
					'value'	=>	$data->value
				);
			}
		}

		$api = new SoapClient(HAET_CLEVERREACH_API_URL);
		try{
			if( $source==null )
				$source = get_bloginfo('name');
			$user = array(
			    "email" => $email,
			    "registered" => time(),
			    "activated" => ( $send_activation_mail ? false : time() ),
			    "source" => $source,
			    "attributes" => $user_data,
			);
			$result = $api->receiverAdd($this->api_key, $list_id, $user);

			if($result->status=="SUCCESS"){                 //successfull list call
				if( $send_activation_mail ){
					$postdata='';
					foreach ($user_data as $user_data_entry) {
						$postdata=$user_data_entry['key'].':'.$user_data_entry['value'].',';
					}
				    $activationdata = array(
				            "user_ip"       => $_SERVER['REMOTE_ADDR'], //the IP of the user who registered. not yours!
				            "user_agent"    => "Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:14.0) Gecko/20100101 Firefox/14.0.1",
				            "referer"       => esc_url( home_url( '/' ) ),
				            "postdata"      => $postdata,
				            "info"          => $source,
				    );
				    $api->formsSendActivationMail($this->api_key, $form_id, $email, $activationdata);
				}

				$return['success'] = true;
			}else{                                          //lists call failed
			    $return['success'] = false;
			    if( $result->message == 'duplicate data' )
			    	$return['message'] = $settings['message_entry_exists'];
			    else
			    	$return['message'] = $result->message;
			}
		} catch(Exception $e){
			$return['success'] = false;
			$return['message'] = __( 'Could not connect to the Cleverreach API.', 'cleverreach' );
		}
		return $return;
	}
}


?>