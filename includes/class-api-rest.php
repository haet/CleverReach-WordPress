<?php
require HAET_CLEVERREACH_PATH . '/vendor/cleverreach_rest_client.php';

class haet_cleverreach_api_rest {
	
	 protected $token;

	 function __construct($token){
	 	$this->token = $token;
	 }


	/**
	 * Test API Key (valid / write-access)
	 *
	 * @return array
	 */
	public function test_token(){
		$test_result=array();

		try{
		    $rest = new CR\tools\rest( HAET_CLEVERREACH_REST_API_URL );
		    $rest->setAuthMode("bearer", $this->token);
		    $result = $rest->get("/attributes");
		    // var_dump($result);
		    
		    // either array of attributes or bool false if no attributes exist (still a valid response)
		    if( is_array($result) || $result === false ){
		        $test_result['success']=true;
		        $test_result['message']=__( 'Successfully connected to Cleverreach.', 'cleverreach' );
		    }else{
		    	$test_result['success']=false;
		    	$test_result['message']=__( 'Unexpected API result', 'cleverreach' );
		    }
		} catch(Exception $e){
		    $test_result['success']=false;
		    $test_result['message']=__( 'Could not connect to the Cleverreach API.', 'cleverreach' ) . ' [ ' .$e->getMessage() . ' ]';
		}

		return $test_result;
	}





	/**
	 * Retreive all lists from cleverreach API
	 *
	 * @return array
	 */
	public function get_lists(){
		try{
			$rest = new CR\tools\rest( HAET_CLEVERREACH_REST_API_URL );
			$rest->setAuthMode("bearer", $this->token);
			$lists = $rest->get("/groups");
			//var_dump( $lists );
			if( !is_array( $lists ) ){
					$return['success']=false;
					$return['message']=__( 'You don\'t have any Cleverreach forms.', 'cleverreach' );
			}else{
				$return['success']=true;
				$return['lists']=array();
				foreach( $lists AS $cr_list ){
					$cr_list->forms = $rest->get("/groups/$cr_list->id/forms");
					$list_stats = $rest->get("/groups/$cr_list->id/stats");
					$cr_list->count = $list_stats->active_count;
					$return['lists'][] = $cr_list;
				}
			}
		} catch(Exception $e){
			$return['success']=false;
			$return['message']=__( 'Could not load lists.', 'cleverreach' ) . ' [ ' .$e->getMessage() . ' ]';
		}
		return $return;
	}




	/**
	 * Retreive list attributes from cleverreach API
	 *
	 * @return array
	 */
	public function get_list_attributes($list_id){
		try{
			$rest = new CR\tools\rest( HAET_CLEVERREACH_REST_API_URL );
			$rest->setAuthMode("bearer", $this->token);

			$global_attributes = $rest->get("/attributes");
			$list_attributes = $rest->get("/attributes", array( 'group_id' => $list_id ));

			if( is_array( $global_attributes ) && count( $global_attributes ) > 0 )
				foreach ($global_attributes as $attribute )	
					$attribute->name = 'GLOBAL_' . $attribute->name;
			else
				$global_attributes = array();

			if( is_array( $list_attributes ) && count( $list_attributes ) > 0 )
				foreach ($list_attributes as $attribute )	
					$attribute->name = 'LIST_' . $attribute->name;
			else
				$list_attributes = array();

			$return['success']=true;
			$return['global_attributes'] = $global_attributes;
			$return['list_attributes'] = $list_attributes;
		} catch(Exception $e){
			$return['success']=false;
			$return['message']=__( 'Could not connect to the Cleverreach API.', 'cleverreach' ) . ' [ ' .$e->getMessage() . ' ]';
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


		try{
			if( $source==null )
				$source = get_bloginfo('name');

			$attributes = array();
			$global_attributes = array();

			foreach ($user_data as $data ) {
			    if( strpos( $data['key'], 'LIST_' ) !== FALSE ){
			        $data['key'] = str_replace( 'LIST_', '', $data['key'] );
			        $attributes[$data['key']] = $data['value'];
			    }elseif( strpos( $data['key'], 'GLOBAL_' ) !== FALSE ){
			        $data['key'] = str_replace( 'GLOBAL_', '', $data['key'] );
			        $global_attributes[$data['key']] = $data['value'];
			    }
			}

			$user = array(
			    "email" => $email,
			    "registered" => time(),
			    "activated" => ( $send_activation_mail ? false : time() ),
			    "source" => $source,
			    "attributes" => $attributes,
			    "global_attributes" => $global_attributes,
			);


			$rest = new CR\tools\rest( HAET_CLEVERREACH_REST_API_URL );
			$rest->setAuthMode("bearer", $this->token);
			$result = $rest->post("/groups/" . $list_id . '/receivers', array( 'postdata' => $user ) );

			//var_dump($result);

			if( is_object($result) && isset( $result->id ) ){
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
				    $rest->post("/forms/" . $form_id . '/send/activate', array( 'email' => $email, 'groups_id' => $list_id, 'doidata' => $activationdata ) );
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
			$emessage = $e->getMessage();
			if ( strpos($emessage, 'duplicate address') !== false) {
				$return['message'] = $settings['message_entry_exists'];
			}else{
				$return['message'] = __( 'Could not connect to the Cleverreach API.', 'cleverreach' ) . ' (' . $emessage . ')';
			}
		}
		return $return;
	}
}


?>