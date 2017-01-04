<?php

if ( ! function_exists('us_sendContact'))
{
	function us_sendContact()
	{
		$errors = array();
		$errors_count = 0;

		// Get page content
		if (empty($_POST['post_id'])){
			$response = array ('success' => 0);
			echo json_encode($response);
			die();
		}

		$post_ID = intval($_POST['post_id']);
		$post = get_post($post_ID);
		if (empty($post)){
			$response = array ('success' => 0);
			echo json_encode($response);
			die();
		}

		$content = $post->post_content;

		// Find CF shortcode
		if ( ! preg_match('/\[vc_contact_form(.*?)\]/', $content, $matches)){
			$response = array ('success' => 0);
			echo json_encode($response);
			die();
		}
		$cf_shortcode = $matches[0];

		$cf_params = shortcode_parse_atts($cf_shortcode);

		$emailTo = get_option('admin_email');

		if ( ! empty($cf_params['form_email'])){
			$emailTo = sanitize_email($cf_params['form_email']);
		}

		if ( ! empty($cf_params['form_captcha']) AND $cf_params['form_captcha'] == 'show'){
			$captcha_salt = (isset($cf_params['captcha_salt']))?$cf_params['captcha_salt']:'wwwlogix';
			$captcha = ( ! empty($_POST['captcha']))?sanitize_text_field($_POST['captcha']):'';
			$captcha_result = ( ! empty($_POST['captcha_result']))?sanitize_text_field($_POST['captcha_result']):'';

			if ($captcha == '' OR (md5($captcha.$captcha_salt) != $captcha_result)) {
				$errors['captcha'] = __('Please, enter correct result', 'us');
				$errors_count++;
			}
		}

		if ($errors_count > 0){
			$response = array ('success' => 0, 'errors' => $errors);
			echo json_encode($response);
			die();
		}

		$body = '';

		$name = ( ! empty($_POST['name']))?sanitize_text_field($_POST['name']):'';
		if ($name != ''){
			$body .= __('Name', 'us').": ".$name."\n";
		}

		$email = ( ! empty($_POST['email']))?sanitize_email($_POST['email']):'';
		if ($email != ''){
			$body .= __('Email', 'us').": ".$email."\n";
		}

		$phone = ( ! empty($_POST['phone']))?sanitize_text_field($_POST['phone']):'';
		if ($phone != ''){
			$body .= __('Phone', 'us').": ".$phone."\n";
		}

		$message = ( ! empty($_POST['message']))?sanitize_text_field($_POST['message']):'';
		if ($message != ''){
			$body .= __('Message', 'us').": ".$message."\n";
		}

		$headers = '';

		wp_mail($emailTo, __('Contact request from', 'us')." http://".$_SERVER['HTTP_HOST'].'/', $body, $headers);

		$response = array ('success' => 1);
		echo json_encode($response);

		die();

	}

	add_action( 'wp_ajax_nopriv_sendContact', 'us_sendContact' );
	add_action( 'wp_ajax_sendContact', 'us_sendContact' );
}
