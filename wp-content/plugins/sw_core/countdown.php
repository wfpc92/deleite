<?php 
/**
 * CountDown
**/
class Ya_CountDown_Shortcode{
	private $id = 0;
    private $addScript = false;
	function __construct(){	
		add_action( 'wp_footer', array( $this, 'CountDown_Script' ) );
		add_shortcode('countdown', array($this, 'CountDown'));
	}
	function CountDown( $atts ){
		$this -> addScript = true;
	extract( shortcode_atts( array(
			'startdate'		=> "2015/06/16 14:00:00",
			'enddate'		=> "2016/01/01 14:00:00"
		), $atts ) );

		$this -> id 	= $this -> id + 1;
		$id 			= $this -> id;
		$data 			= 'data-start="'.$startdate.'" data-end="'.$enddate.'"';
		$output 		= '<div class="countdown-shortcode">';
		$output 		.= '<div class="comming-soon" id="comming_soon_'.$id.'" '. $data .'>';
		$output 		.= '</div></div>';
		return $output;
	}
	function CountDown_Script(){
		if( !$this -> addScript ){
			return false;
		}
		wp_register_script( 'knob', plugins_url( '/js/jquery.knob.js', __FILE__ ), array(), null, true);	
		if (!wp_script_is('knob')) {
			wp_enqueue_script( 'knob' );
		}
		wp_register_script( 'throttle', plugins_url( '/js/jquery.throttle.js', __FILE__ ), array(), null, true);	
		if (!wp_script_is('throttle')) {
			wp_enqueue_script( 'throttle' );
		}
		$script = '';
		$script .= '<script type="text/javascript">
			 jQuery(function($){
				 "use strict";
				$(document).ready(function(){
					 $(".comming-soon").each(function(){
						var $id 	= $("#" + this.id);
						var $start 	= $id.data("start");
						var $end 	= $id.data("end");
						var $now = 0;
						if( $start = "" ){
							$now = new Date($start).getTime()/1000;
						}else{
							$now = $.now()/1000;
						}
						var $austDay = new Date($end).getTime()/1000;
						$id.ClassyCountdown({
							theme: "flat-colors",
							end: $austDay,
							now: $now,
							labelsOptions: {
								lang: {
									days: "Days",
									hours: "Hours",
									minutes: "Minutes",
									seconds: "Seconds"
								},
								style: "color: #444444;"
							},
							style: {
								days: {
									gauge: {
										thickness: .05,
										fgColor: "#94c300",
										bgColor: "#b9babb",										
									},									
								},
								hours: {
									gauge: {
										thickness: .05,
										fgColor: "#94c300",
										bgColor: "#b9babb",										
									},									
								},
								minutes: {
									gauge: {
										thickness: .05,
										fgColor: "#94c300",
										bgColor: "#b9babb",										
									},									
								},
								seconds: {
									gauge: {
										thickness: .05,
										fgColor: "#94c300",
										bgColor: "#b9babb"							
									},									
								}

							}
						});
					 });
				});
			});
		</script>';
		echo $script;
	}
}
new Ya_CountDown_Shortcode();