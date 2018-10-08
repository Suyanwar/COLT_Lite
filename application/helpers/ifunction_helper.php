<?php

function exc_account($id)
{
	switch($id){
		case 'mra':
			return array('363', '209', '360', '367', '265', '370', '362', '364', '365', '368', '369', '361', '372', '373', '374', '375');
			break;
			
		case 'n3o':
			return array('401', '402', '397');
			break;
	}
}

function action_response($status, $form_id, $css, $message, $js = '')
{
	return '<div class="'.$css.'">'.$message.'</div><script>iFresponse('.$status.', "'.$form_id.'");'.$js.'</script>';
}

function slidedown_response($form_id, $css, $message, $js = '')
{
	return '<div class="'.$css.'">'.$message.'</div><script>$("#'.$form_id.'").slideDown();'.$js.'</script>';
}

function active_menu($current, $str)
{
	if($current == $str){
		echo ' class="active"';
	}
}

function ext_link($socmed, $socmed_id, $username)
{
	return (($socmed == 'facebook') ? 'https://www.facebook.com/'.$socmed_id : (($socmed == 'twitter') ? 'https://twitter.com/'.$username : 'https://www.instagram.com/'.$username));
}

function arr_month($id=0)
{
	return array($id => 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
}

function days_between($from, $to)
{
	return abs(strtotime($from) - strtotime($to))/60/60/24;
}

function passwd($str)
{
	// return md5(crypt($str, config_item('password_salt')).'+ salt +');
	return md5($str);
}

function input($str)
{
	return htmlspecialchars($str);
}

function option_selected($value, $label, $selected)
{
	$data = '<option value="'.$value.'"';
	$data .= ($value == $selected) ? ' selected="selected"' : '';
	$data .= '>'.$label.'</option>';
	return $data;
}

function checked($id)
{
	return $id ? ' checked="checked"' : '';
}

function un_link($url)
{
	if(file_exists($url)) unlink($url);
	return true;
}

function sort_array($datas)
{
	$sortArray = array();
	foreach($datas as $dt){
		foreach($dt as $key => $value){
			if(!isset($sortArray[$key])){
				$sortArray[$key] = array();
			}
			$sortArray[$key][] = $value;
		}
	}
	return $sortArray;
}

function connection_abort()
{
	if(connection_aborted()) die();
}

function lastmonth()
{
	$month = date('M Y', strtotime("-1 month"));
	if($month == date('M Y')){
		$month = date('M Y', strtotime("-1 month -1 day"));
	}
	return '01 '.$month.' ~ '.date('t M Y', strtotime('01 '.$month));
}

function formatmonth($from, $to)
{
	return date('d M Y', $from).' ~ '.date('d M Y', $to);
}

function getmonth($id, $str)
{
	$date = explode(' ~ ', $str);
	switch($id){
		case 'unix';
			return array(strtotime($date[0]), strtotime($date[1]));
			break;
			
		case 'date';
			return array(date('Y-m-d', strtotime($date[0])), date('Y-m-d', strtotime($date[1])));
			break;
	}
}

function getday($small_unix, $biggest_unix)
{
	return abs($small_unix - $biggest_unix) / 60 / 60 / 24;
	
	/* usage e.g:
	$days = getday($unix[0], $unix[1]);
	for($i=0; $i <= $days; $i++){
		echo $i ? ',' : '';
		echo '"'.date('M,d', strtotime("+{$i} day", $unix[0])).'"';
	}*/
}

function limit_text($str, $limit)
{
	if(str_word_count($str, 0) > $limit){
		$words = str_word_count($str, 2);
		$pos = array_keys($words);
		$str = substr($str, 0, $pos[$limit]).'...';
	}
	return $str;
}

function range_week($date)
{
	$dt = strtotime($date);
	$res['start'] = (date('N', $dt) == 7) ? date('Y-m-d', $dt) : date('Y-m-d', strtotime('last sunday', $dt));
	$res['end'] = (date('N', $dt) == 6) ? date('Y-m-d', $dt) : date('Y-m-d', strtotime('next saturday', $dt));
	return $res;
}

function count_weeks($date)
{
	$dt = strtotime($date);
	$month = date('m', $dt);
	$year = date('Y', $dt);
	$firstday = date('w', mktime(0, 0, 0, $month, 1, $year));
	$lastday = date('t', mktime(0, 0, 0, $month, 1, $year));
	$no_of_weeks = 1;
	$count_weeks = 0;
	while($no_of_weeks <= ($lastday+$firstday)){
		$no_of_weeks += 7;
		$count_weeks++;
	}
	return $count_weeks;
}

function filter_month($month, $date)
{
	$m = round($month);
	$mm = date('n', strtotime($date));
	
	if($mm <> $m){
		
		$y = date('Y', strtotime($date));
		if($mm < $m){
			$y = ($y -1);
		}
		
		if(date('j', strtotime($date)) < 7){
			return $y.'-'.$month.'-'.number_of_days($y.'-'.$month.'-01');
		}
		else return $y.'-'.$month.'-01';
	}
	else return $date;
}

function number_of_days($date)
{
	return date('t', mktime(0, 0, 0, date('m', strtotime($date)), 1, date('Y', strtotime($date))));
}

function curl_post($host, $fields, $return=0, $timeout=0)
{
	$sep = '';
	$fields_string = '';
	foreach($fields as $key => $value){
		$fields_string .= $sep.$key.'='.$value;
		$sep = '&';
	}
	
	$ch = curl_init();
	curl_setopt_array($ch,
		array(
			CURLOPT_URL => $host,
			CURLOPT_POST => count($fields),
			CURLOPT_POSTFIELDS => $fields_string,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_RETURNTRANSFER => $return,
			CURLOPT_CONNECTTIMEOUT => 0,
			CURLOPT_TIMEOUT => $timeout
		)
	);
	$returns = curl_exec($ch);
	curl_close($ch);
	return $return ? $returns : false;
}

function curl_file_get_contents($host)
{
	$ch = curl_init();
	curl_setopt_array($ch,
		array(
			CURLOPT_URL => $host,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_CONNECTTIMEOUT => 0,
			CURLOPT_COOKIE => NULL,
			CURLOPT_NOBODY => false
		)
	);
	$returns = curl_exec($ch);
	curl_close($ch);
	return $returns ? $returns : false;
}

function str_checking($type, $str, $arr=array())
{
	switch($type){
		case 'in_array':
			return in_array($str, $arr, true) ? $str : '';
			break;
			
		case 'email':
			return preg_match("/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i", $str) ? $str : false;
			break;
			
		case 'url':
			return preg_match("/^(http\:\/\/|https\:\/\/)?([0-9a-zA-Z][0-9a-zA-Z\-]*\.)+[0-9a-zA-Z][0-9a-zA-Z\_\-\s\.\/\?\%\#\&\=]*$/i", $str) ? $str : false;
			break;
			
		case 'sql':
			return str_replace("'", "''", $str);
			break;
			
		case 'digit':
			return ctype_digit($str) ? $str : '';
			break;
	}
}

function str_replacing($type, $str)
{
	switch($type){
		case 'alpha_num':
			return preg_replace('/[^a-zA-Z0-9]/i', '', $str);
			break;
			
		case 'alpha':
			return preg_replace('/[^a-zA-Z]/i', '', $str);
			break;
			
		case 'digit':
			return preg_replace('/[^0-9]/i', '', $str);
			break;
			
		case 'float':
			return preg_replace('/[^0-9.-]/i', '', $str);
			break;
	}
}

function url($type, $uri='')
{
	switch($type){
		case 'facebook':
			return 'https://graph.facebook.com/'.$uri;
			break;
		
		case 'twitter':
			return 'https://api.twitter.com/1.1/'.$uri;
			break;
		
		case 'instagram':
			return 'https://api.instagram.com/v1/'.$uri;
			break;
		
		case 'youtube':
			return 'https://www.googleapis.com/youtube/v3/'.$uri;
			break;
	}
}