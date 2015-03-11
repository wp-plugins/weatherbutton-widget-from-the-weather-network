<?php
/*
Plugin Name: The Weather Network 
Description: Display your city's weather on the sidebar. Choose from a variety of designs and sizes.
Author: The Weather Network
Version: 1.0
Author URI: http://www.theweathernetwork.com
Plugin URI: http://widget.twnmm.com/twn-weather.zip
*/



function TWN_weather_init() {

    if(!function_exists('register_sidebar_widget') || !function_exists('register_widget_control'))
		return; 

    function TWN_weather_control(){
        $newoptions = get_option('TWN_weather');
    	$options = $newoptions;
		$options_flag=0;
    	if(empty($newoptions)){
			$options_flag=1;
			$newoptions = array(
				'widgetID'=>'201', 
				'placecode' => 'CAON0696'
			);
		}

		if($_POST['TWN-weather-submit']){
			$options_flag=1;
			$newoptions['widgetID'] = strip_tags(stripslashes($_POST['TWN-weather-widgetID']));
			$newoptions['placecode'] = strip_tags(stripslashes($_POST['TWN-weather-placecode']));
		}

      	if ( $options_flag ==1 ) {
              $options = $newoptions;
              update_option('TWN_weather', $options);
      	}

      	$widgetID = htmlspecialchars($options['widgetID'], ENT_QUOTES);
      	$placecode = htmlspecialchars($options['placecode'], ENT_QUOTES);
		
		echo "\n";
       
		// Get city
		echo '<p><label for="TWN-weather-city"><a href="http://www.theweathernetwork.com/weather-widget" target="_blank">Click here</a> to Customize your Weather Widget. Replace the default Widget ID and Placecode below with the one provided on the customization screen.</label></p>';
        echo '<p><label for="TWN-weather-widgetID">Widget ID: <input style="width: 90%;" id="TWN-weather-widgetID" name="TWN-weather-widgetID" type="text" value="'.$widgetID.'" /> </label></p>';
		echo '<p><label for="TWN-weather-placecode">First location\'s code: <input style="width: 90%;" id="TWN-weather-placecode" name="TWN-weather-placecode" type="text" value="'.$placecode.'" /> </label></p>';
		
      	// Hidden "OK" button
      	echo '<label for="TWN-weather-submit">';
      	echo '<input id="TWN-weather-submit" name="TWN-weather-submit" type="hidden" value="Ok" />';
      	echo '</label>';
		


		echo "\n";
        echo '<p>*Save after each selection</p>';	


    }


    /////////////////////////////////////////////////////////////////////////////////////////////////////
    //
    //	DISPLAY WEATHER WIDGET
    //
    /////////////////////////////////////////////////////////////////////////////////////////////////////

     function TWN_weather($args){

		// Get values 
      	extract($args);
      	$options = get_option('TWN_weather');

		// Get Metric/Imperial,Buttontype	
      	$widgetID = htmlspecialchars($options['widgetID'], ENT_QUOTES);
      	$placecode = htmlspecialchars($options['placecode'], ENT_QUOTES);
		

		// Get city 
		echo '<!--start of code - The Weather Network '. $placecode .' -->';
		$urlstring="";
		
		//global $wp_version;
		$widget_call_string=wp_remote_get('http://widget.twnmm.com/widget.php?placecode='.$placecode.'&widgetid='.$widgetID);	

		echo $widget_call_string['body'];
    }
  
    register_sidebar_widget('The Weather Network', 'TWN_weather');
    register_widget_control('The Weather Network', 'TWN_weather_control', 245, 300);


}


add_action('plugins_loaded', 'TWN_weather_init');

?>