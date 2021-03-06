<?php
if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Remove Form Action
add_filter( 'salesforce_w2l_form_action', 'salesforce_w2l_form_action_example', 10, 1 );
function salesforce_w2l_form_action_example(  $action ){

	return $action;

}

// Lead Source
add_filter( 'salesforce_w2l_lead_source', 'salesforce_w2l_lead_source_example', 10, 2 );
function salesforce_w2l_lead_source_example(  $lead_source, $form_id ){

	if( $form_id == 1 )
		return 'Example Lead Source for Form #1 on page id #'.get_the_id();

	return $lead_source;

}

add_filter( 'salesforce_w2l_post_args', 'salesforce_w2l_post_args_example' );

function salesforce_w2l_post_args_example( $args ){

	$args['timeout'] = 10; // http timeout in seconds
	return $args;

}

add_filter( 'salesforce_w2l_post_data', 'salesforce_w2l_post_data_example', 10, 3 );

function salesforce_w2l_post_data_example( $post, $form_id, $form_type ){
	error_log( 'POST ARGS '.print_r( $post, 1 ) );
	$post['test'] = 'test';
	return $post;
}


add_filter( 'salesforce_w2l_post_data', 'salesforce_w2l_post_data_date_example', 10, 3 );

function salesforce_w2l_post_data_date_example( $post, $form_id, $form_type ){

	$date_fields = array( 'your_field_name', 'your_other_field_name' );

	foreach( $post as $key=>$val ){
		if( in_array( $key, $date_fields )  )
			$post[$key] = date( 'm/d/Y', strtotime( $val ) );
	}

	return $post;
}


add_action('salesforce_w2l_before_submit', 'salesforce_w2l_before_submit_example', 10, 3 );

function salesforce_w2l_before_submit_example( $post, $form_id, $form_type ){
	error_log( 'BEFORE SUBMIT '.print_r($post,1) );
}

add_action('salesforce_w2l_error_submit', 'salesforce_w2l_error_submit_example', 10, 4 );

function salesforce_w2l_error_submit_example( $result, $post, $form_id, $form_type ){
	error_log( 'ERROR SUBMIT ' . print_r($result,1) );
}

add_action('salesforce_w2l_after_submit', 'salesforce_w2l_after_submit_example', 10, 3 );

function salesforce_w2l_after_submit_example( $post, $form_id, $form_type ){
	error_log( 'AFTER SUBMIT '.print_r($post,1) );
}


add_filter( 'salesforce_w2l_post_args', 'salesforce_w2l_post_args_timeout_example', 10, 1 );

function salesforce_w2l_post_args_timeout_example( $args ){
	$args['timeout'] = 10;
	return $args;
}

//add_filter( 'salesforce_w2l_show_admin_nag_message', '__return_false', 10, 1 );

add_filter( 'salesforce_w2l_field_value', 'salesforce_w2l_field_value_referrer_example', 10, 3 );

function salesforce_w2l_field_value_referrer_example( $val, $field, $form ){

	$form_id = 1; // form id to act upon
	$field_name = 'referrer__c'; // API Name of the field your want to autofill

	if( $form == $form_id && $field_name == $field ){
		if( isset( $_SERVER['HTTP_REFERER'] ) ){
			return $_SERVER['HTTP_REFERER'];
		}
	}

	return $val;

}

add_filter( 'salesforce_w2l_field_value', 'salesforce_w2l_field_value_querystring_example', 10, 3 );

function salesforce_w2l_field_value_querystring_example( $val, $field, $form ){

	$form_id = 1; // form id to act upon
	$field_name = 'source__c'; // API Name of the field your want to autofill
	$qs_var = 'source'; // e.g. ?source=foo

	if( $form == $form_id && $field_name == $field ){
		if( isset( $_GET[ $qs_var ] ) ){
			return $_GET[ $qs_var ];
		}
	}

	return $val;

}

add_filter( 'salesforce_w2l_field_value', 'salesforce_w2l_field_value_geoip_example', 10, 3 );

function salesforce_w2l_field_value_geoip_example( $val, $field, $form ){

	if( !function_exists( 'geoip_detect2_get_info_from_current_ip' ) ) return;

	$form_id = 1; // form id to act upon
	$field_name = 'country__c'; // API Name of the field your want to autofill

	if( $form == $form_id && $field_name == $field ){

		$userInfo = geoip_detect2_get_info_from_current_ip();
		//$val = $userInfo->country->isoCode; // e.g. US
		$val = $userInfo->country->name; // e.g. United States

	}

	return $val;

}

// salesforce_w2l_returl_{Form ID}
//add_filter( 'salesforce_w2l_returl_1', 'salesforce_w2l_returl_1_tester_example', 10, 1 );
function salesforce_w2l_returl_1_tester_example(  $returl ){

	return 'http://123.com';

}

// salesforce_w2l_success_message_{Form ID}
add_filter( 'salesforce_w2l_success_message_1', 'salesforce_w2l_success_message_1_tester_example', 10, 1 );
function salesforce_w2l_success_message_1_tester_example(  $success ){

	return 'Testing 123';

}

add_filter( 'salesforce_w2l_field_value', 'salesforce_w2l_field_value_date_example', 10, 3 );

function salesforce_w2l_field_value_date_example( $val, $field, $form ){

$form_id = 7; // form id to act upon [salesforce form="7"]
$field_name = 'Date_Received__c'; // API Name of the field you want to auto check

if( $form == $form_id && $field_name == $field && ! $_POST )
return current_time('Y-m-d'); // or whatever date format you want

return $val;
}

add_filter( 'salesforce_w2l_post_args', 'example_salesforce_enable_ssl_verify', 10, 1 );

function example_salesforce_enable_ssl_verify( $args ){

	$args['sslverify'] = true;
	return $args;

}

add_filter('sfwp2l_validate_field','block_non_biz_emails', 10, 4);

function block_non_biz_emails( $error, $name, $val, $field ){

	if( $name == 'email' ){

		$non_biz_domains = array( 'gmail.com', 'yahoo.com', 'hotmail.com', 'aol.com' );

		$parts = explode( '@', $val );

		$domain = array_pop( $parts );

		if( in_array( $domain, $non_biz_domains ) ){
			$error['valid'] = false;
			$error['message'] = 'Please enter a business email addresss.';
		}

	}

	return $error;
}

add_filter( 'salesforce_w2l_form_html' ,'salesforce_w2l_form_html_add_title', 10, 5 );

function salesforce_w2l_form_html_add_title( $content, $form_options, $is_sidebar, $form_id, $version ){

		return '<h2>'.get_the_title( $form_id ).'</h2>' . $content;

}

add_filter('salesforce_w2l_cc_admin_email_subject', 'salesforce_w2l_cc_admin_email_subject_add_form_title', 10, 3 );

function salesforce_w2l_cc_admin_email_subject_add_form_title( $subject, $form_type, $post ){

	// extract form ID from $post
	$form_id = absint( $_POST['form_id'] );

	// get form options
	$options = get_option('salesforce2');

	// alter subject line
	$subject = str_replace( 'Salesforce Web to Lead Submission', '[SFWP2L] ' .  $options['forms'][ $form_id ][ 'form_name' ] , $subject );

	return $subject;

}