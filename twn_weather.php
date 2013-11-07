<?php
/*
Plugin Name: The Weather Network 
Description: Display your city's weather on the sidebar. Choose from a variety of designs and sizes.
Author: The Weather Network
Version: 1.0
Author URI: http://www.theweathernetwork.com
Plugin URI: http://www.theweathernetwork.com/weatherplugin/wordpress/twn-weather.zip
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
				'fahrenheitflag'=>'0', 
				'city' => 'city|CAON0696',
				'city1' => '',
				'city2' => '',
				'city3' => '',
				'city4' => '',
				'buttontype' => 'darkbg',
				'multiplecity'=>'0'
			);
		}

		if($_POST['TWN-weather-submit']){
			$options_flag=1;
			$newoptions['fahrenheitflag'] = strip_tags(stripslashes($_POST['TWN-weather-fahrenheitflag']));
			$newoptions['city'] = strip_tags(stripslashes($_POST['TWN-weather-city']));
			$newoptions['city1'] = strip_tags(stripslashes($_POST['TWN-weather-city1']));
			$newoptions['city2'] = strip_tags(stripslashes($_POST['TWN-weather-city2']));
			$newoptions['city3'] = strip_tags(stripslashes($_POST['TWN-weather-city3']));
			$newoptions['city4'] = strip_tags(stripslashes($_POST['TWN-weather-city4']));
			$newoptions['buttontype'] = strip_tags(stripslashes($_POST['TWN-weather-buttontype']));			
			$newoptions['multiplecity'] = strip_tags(stripslashes($_POST['TWN-weather-multiplecity']));		
		}

      	if ( $options_flag ==1 ) {
              $options = $newoptions;
              update_option('TWN_weather', $options);
      	}

      	$fahrenheit_flag = htmlspecialchars($options['fahrenheitflag'], ENT_QUOTES);
      	$city = htmlspecialchars($options['city'], ENT_QUOTES);
		$city1 = htmlspecialchars($options['city1'], ENT_QUOTES);
		$city2 = htmlspecialchars($options['city2'], ENT_QUOTES);
		$city3 = htmlspecialchars($options['city3'], ENT_QUOTES);
		$city4 = htmlspecialchars($options['city4'], ENT_QUOTES);
		$buttontype=htmlspecialchars($options['buttontype'], ENT_QUOTES);
		$multiplecity = htmlspecialchars($options['multiplecity'], ENT_QUOTES);
		
		echo "\n";
        echo '<p><label for="TWN-weather-buttontype"> Button Type: <select id="TWN-weather-buttontype" name="TWN-weather-buttontype"><option value="darkbg" ';
		if($buttontype=="darkbg") echo "selected"; 
		echo '>Dark</option><option value="darkbg_noST" ';
		if($buttontype=="darkbg_noST") echo "selected"; 
		echo '>Dark with current conditions only</option><option value="lightbg" ';
		if($buttontype=="lightbg") echo "selected"; 
		echo '>Light</option><option value="lightbg_noST" ';
		if($buttontype=="lightbg_noST") echo "selected"; 
		echo '>Light with current conditions only</option></select></label></p>';

		//   Fahrenheit option
		$fahrenheit_checked = "";
		if ($fahrenheit_flag =="1")
			$fahrenheit_checked = "CHECKED";
		echo "\n";
        echo '<p><label for="TWN-weather-fahrenheitflag">Measurement: ';
		
		echo '<select id="TWN-weather-fahrenheitflag" name="TWN-weather-fahrenheitflag"><option value="0"';
		if($fahrenheit_flag =="0") echo "selected"; 
		echo '>Metric</option><option value="1" ';
		if($fahrenheit_flag =="1") echo "selected"; 
		echo '>Imperial</option></select></label></p>';			
		

		echo "\n";
        echo '<p><label for="TWN-weather-multiplecity">Single/Multiple city: ';
		
		echo '<select id="TWN-weather-multiplecity" name="TWN-weather-multiplecity"><option value="0"';
		if($multiplecity =="0") echo "selected"; 
		echo '>Single City</option><option value="1" ';
		if($multiplecity =="1") echo "selected"; 
		echo '>Multiple City</option></select></label></p>';	

		// Get city
		echo '<p><label for="TWN-weather-city"><a href="http://www.theweathernetwork.com/weather_centre/cmswxbuttons" target="_blank">Click here</a> to get the location code.</label></p>';
        echo '<p><label for="TWN-weather-city1">First location\'s code: <input style="width: 90%;" id="TWN-weather-city" name="TWN-weather-city" type="text" value="'.$city.'" /> </label></p>';
		echo '<p><label for="TWN-weather-city2">Second location\'s code: <input style="width: 90%;" id="TWN-weather-city1" name="TWN-weather-city1" type="text" value="'.$city1.'" /> </label></p>';
		echo '<p><label for="TWN-weather-city3">Third location\'s code: <input style="width: 90%;" id="TWN-weather-city2" name="TWN-weather-city2" type="text" value="'.$city2.'" /> </label></p>';
		echo '<p><label for="TWN-weather-city4">Fourth location\'s code: <input style="width: 90%;" id="TWN-weather-city3" name="TWN-weather-city3" type="text" value="'.$city3.'" /> </label></p>';
		echo '<p><label for="TWN-weather-city5">Fifth location\'s code:: <input style="width: 90%;" id="TWN-weather-city4" name="TWN-weather-city4" type="text" value="'.$city4.'" /> </label></p>';
	
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
      	$fahrenheitflag = htmlspecialchars($options['fahrenheitflag'], ENT_QUOTES);
      	$buttontype = htmlspecialchars($options['buttontype'], ENT_QUOTES);
		

		// Get city 
		echo '<!--start of code - The Weather Network '. $city .' -->';
		$multiplecity= htmlspecialchars($options['multiplecity'], ENT_QUOTES);
		$urlstring="";
		$citycount=0;
		for($i=0;$i<5;$i++){ 
			if($i==0){ $city=htmlspecialchars($options['city'], ENT_QUOTES);}
			if($i==1){ $city=htmlspecialchars($options['city1'], ENT_QUOTES);}
			if($i==2){ $city=htmlspecialchars($options['city2'], ENT_QUOTES);}
			if($i==3){ $city=htmlspecialchars($options['city3'], ENT_QUOTES);}
			if($i==4){ $city=htmlspecialchars($options['city4'], ENT_QUOTES);}
			if((isset($city))&&($city!="")){
				$cityarray=explode("|",$city);
				$producttype=$cityarray[0];
				$locationcode=$cityarray[1];				
			}else{
				break;
			}
			if($i==0){ $urlstring=$urlstring."&producttype=".$producttype."&locationcode=".$locationcode;}
			if($i==1){ $urlstring=$urlstring."&producttype1=".$producttype."&locationcode1=".$locationcode;}
			if($i==2){ $urlstring=$urlstring."&producttype2=".$producttype."&locationcode2=".$locationcode;}
			if($i==3){ $urlstring=$urlstring."&producttype3=".$producttype."&locationcode3=".$locationcode;}
			if($i==4){ $urlstring=$urlstring."&producttype4=".$producttype."&locationcode4=".$locationcode;}
			$citycount++;
		}
		$weatherdatatype="c";
		if($fahrenheitflag =="1"){
			$weatherdatatype="f";
		}

		if(($multiplecity =="0")||($citycount<2)){
			$buttontype=$buttontype."_singlecity";
		}
		global $wp_version;
		$widget_call_string="http://www.theweathernetwork.com/index.php?product=weatherbutton&buttontype=".$buttontype.$urlstring."&switchto=".$weatherdatatype."&cms=WordPress&version=".$wp_version;
		echo '<script type="text/javascript" src="'.$widget_call_string . '"></script>';
		echo '<!-end of code-->';
    }
  
    register_sidebar_widget('The Weather Network', 'TWN_weather');
    register_widget_control('The Weather Network', 'TWN_weather_control', 245, 300);


}


add_action('plugins_loaded', 'TWN_weather_init');

?>