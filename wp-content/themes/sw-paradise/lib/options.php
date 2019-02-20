<?php
/*
 *
 * Require the framework class before doing anything else, so we can use the defined urls and dirs
 * Also if running on windows you may have url problems, which can be fixed by defining the framework url first
 *
 */
if(!class_exists('SW_PARADISE_Options')){
	require_once( get_template_directory().'/lib/options/options.php' );
}

function add_field_rights($field, $options){	
	if ( key_exists( $field['id'], array( 'show-cpanel' => '1', 'widget-advanced' => '1' )) || key_exists( $field['type'], array( 'upload' => '1' )) ) {
		return;
	}
	
	// build id for customize_right
	// get value from $options->options[ id ]
	$customize = array(
		'id' => $field['id'].'_customize_allow',
		'type' => 'checkbox',
		'title' => 'x',
		'sub_desc' => '',
			'desc' => '',
		'sub_option' => true
		);
	$options->_field_input($customize);
		
	$cpanel = array(
		'id' => $field['id'].'_cpanel_allow',
		'type' => 'checkbox',
		'title' => 'x',
		'sub_desc' => '',
			'desc' => '',
		'std' => false,
		'sub_option' => true
		);
	$options->_field_input($cpanel);
		
}
if ( is_admin()){
	add_filter('sw-paradise-opts-rights', 'add_field_rights', 10, 2);
}

function sw_paradise_options(){
	global $sw_paradise_options;
	return $sw_paradise_options;
}

$add_query_vars = array();
function sw_paradise_query_vars( $qvars ){
	global $options, $add_query_vars;
	
	foreach ($options as $option) {
		if (isset($option['fields'])) {
			
			foreach ($option['fields'] as $field) {
				$add_query_vars[] = $field['id'];
			}
		}
	}
	
	if ( is_array($add_query_vars) ){
		foreach ( $add_query_vars as $field ){
			$qvars[] = $field;
		}
	}
	
	return $qvars;
}

function sw_paradise_parse_request( &$wp ){
	global $add_query_vars, $options_args;
	
	if ( is_array($add_query_vars) ){
		foreach ( $add_query_vars as $field ){
			if ( array_key_exists($field, $wp->query_vars) ){
				$current_value = sw_paradise_options()->get($field);
				$request_value = $wp->query_vars[$field];
				$field_name = $options_args['opt_name'] . '_' . $field;
				if ($request_value != $current_value){
					setcookie(
						$field_name,
						$request_value,
						time() + 86400,
						'/',
						COOKIE_DOMAIN,
						0
					);
					if (!isset($_COOKIE[$field_name]) || $request_value != $_COOKIE[$field_name]){
						$_COOKIE[$field_name] = $request_value;
					}
				}
			}
		}
	}
}

if (!is_admin()){
	add_filter('query_vars', 'sw_paradise_query_vars');
	add_action('parse_request', 'sw_paradise_parse_request');
}
?>