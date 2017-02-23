<?php
date_default_timezone_set('Asia/Bangkok');
$time = date("H:i:s");

function randomCitizenID(){
	for($i=0;$i<12;$i++){
		$k = abs($i + (-13));
		$m = rand(0,9);
		$firstNumber .= $m; // ตัวเลขชุดแรก (12 หลัก)
		$numberCalc += ($k * $m);
	}
	$lastNumber = 11 - ($numberCalc % 11); // ตัวเลขหลักสุดท้าย
	return $firstNumber.$lastNumber;
}
function get_data_ip($url_R) {
	$chIP = curl_init();
	$timeout = 5;
	curl_setopt($chIP, CURLOPT_URL, $url_R);
	curl_setopt($chIP, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($chIP, CURLOPT_CONNECTTIMEOUT, $timeout);
	$dataR = curl_exec($chIP);
	curl_close($chIP);
	return $dataR;
}
function send_sms($to,$from,$msg_sms){
	$url = 'https://rest.nexmo.com/sms/json?' . http_build_query(
    [
      'api_key' =>  'xxxx',
      'api_secret' => 'xxxx',
      'to' => $to,
      'from' => $from,
      'text' => $msg_sms,
      'type' => 'unicode'
    ]
);
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);

return $response;
}
// parameters
$hubVerifyToken = 'xxxx';
$accessToken = "xxxx";
// check token at setup
if ($_REQUEST['hub_verify_token'] === $hubVerifyToken) {
  echo $_REQUEST['hub_challenge'];
  exit;
}
// handle bot's anwser
$input = json_decode(file_get_contents('php://input'), true);
$senderId = $input['entry'][0]['messaging'][0]['sender']['id'];
$messageText = $input['entry'][0]['messaging'][0]['message']['text'];
//$answer = "ว่าไง boss มีไรให้ช่วยมั๊ย";

if($messageText == "หวัดดี" || $messageText == "สวัสดี" || $messageText == "ทักๆ" || $messageText == "ทัก" || $messageText == "hi" || $messageText == "hello") {
    $answer = "สัวสดีคับ boss\n"."หากสงสัยอะไรพิมพ์ว่า help ได้น่ะ";
}else if($messageText == "วันนี้วันที่เท่าไร" || $messageText == "วันที่" || $messageText == "/date"){
	$date = date("d-m-Y");
   	$answer = "วันนี้วันที่ ".$date;

}else if($messageText == "กี่โมงแล้ว" || $messageText == "ตอนนี้เวลา"){
	 $answer = "ตอนนี้เวลา ".$time;
}else if($messageText == "จ้า" || $messageText == "ขอบคุณ" || $messageText == "ขอบใจ" || $messageText == "อิอิ"){
	$answer = "อิอิ :)";
}else if($messageText == "ตารางเรียน" || $messageText == "ขอดูตารางเรียน"){
$answer = "เปิดดูได้ที่ >> "."https://www.facebook.com/photo.php?fbid=1170193793043479&set=g.494651737336187&type=1&theater"." เลยคับ";

}else if($messageText == "help"){
$answer = "เปิดดูได้ที่ >> "."https://www.facebook.com/notes/anuwat-khongchuai/x-bot-help/1827465617539734"." เลยคับ";

}else if(strpos($messageText,'ทำไร') !== false){
		$answer = "เล่นเฟสบุ๊ค <3";

}else if(strpos($messageText,'สั้ส') !== false || strpos($messageText,'ไอ้เหี้ย') !== false || strpos($messageText,'พ่อมึงตาย') !== false || strpos($messageText,'ควย') !== false || strpos($messageText,'เย็ด') !== false){
	
	$random_answer_rude = array(":(","แม่มึงดิ","ไอ้สัส","ควยไร","ไอ้เหี้ย","fuck you");
	$answer = $random_answer_rude[rand(0,5)];

}
else if($messageText == "/"){
	 
	$answer = "/md5encrypt\n/md5decrypt\n/showsqlimagic\n/reverse_ip\n/whois\n/whatip\n/rtpasswd\n/time\n/date\n/randnum\n/thairandom_id\n/ipinf\n/hostname2ip\n/sendsms\n/pincode";
}
else if(strpos($messageText,'กินไรยัง') !== false){
	$random_answer_eat = array(":)","กินข้าวแล้ว","ยังเลย","ยัง ไม่หิวคับ","จะเลี้ยงหรา","กินไม่ลง");
	$answer = $random_answer_eat[rand(0,5)];
}else if( strpos($messageText,'เงี่ยน') !== false ){

	$answer = "ไปชักว่าวววววว";	

}else if(strpos($messageText,'/md5encrypt') !== false){
		$md5text = substr($messageText,12,strlen($messageText));
		$answer = "md5: ".md5($md5text);
}else if(strpos($messageText,"/showsqlimagic") !== false){
		$answer = "a' or 'a'='a\n".'" or 1=1-'."\n"."or 1=1--\n";

}else if(strpos($messageText,"/reverse_ip") !== false){

	$dns_reverseip = substr($messageText,12,strlen($messageText));


	$answer = "Result >> <3 :p http://reip.stephack.com/".$dns_reverseip;
}
//-------------------------------------------------------------------------------------------------------

else if(strpos($messageText,'/whois') !== false){

	$dns_whois = substr($messageText,7,strlen($messageText));
	$dnsurl_whois = 'https://who.is/whois/'.$dns_whois;
	$answer = "Result >> <3 :p ".$dnsurl_whois;

}else if(strpos($messageText,'/whatip') !== false){
	$answer = "http://whatismyipaddress.com/";

}else if(strpos($messageText,'/rtpasswd') !== false){
	$answer = "https://portforward.com/router-password/";
}else if( $messageText == "/time"){
	$answer = "Time Now: ".$time;

}else if( $messageText == "/randnum"){
	$answer = "Random Num: ".rand();

}else if( $messageText == "/thairandom_id"){
	$answer = "Random ID: ".randomCitizenID();

}else if(strpos($messageText,"/md5decrypt") !== false){
	$hash = substr($messageText,12,strlen($messageText));
	$hash_type = "md5";
	$email = "xxxx";
	$code = "xxxx";
	$response = file_get_contents("http://md5decrypt.net/Api/api.php?hash=".$hash."&hash_type=".$hash_type."&email=".$email."&code=".$code);
	$answer = "<3 Result == : ".$response;
}else if(strpos($messageText,"/ipinf") !== false){
	$iptogetdetail = substr($messageText,7,strlen($messageText));
	$response_detail = get_data_ip("http://ipinfo.io/".$iptogetdetail);
	$answer = $response_detail;
}else if(strpos($messageText,"/sendsms") !== false){
	$dataSMS = explode("_", $messageText);
	$to = $dataSMS[1];
	$from = $dataSMS[2];
	$msg_sms = $dataSMS[3];
	$answer = send_sms($to,$from,$msg_sms);
}else if(strpos($messageText,"/pincode") !== false){
	$datapincode = explode("|", $messageText);
	$to_pincode = $datapincode[1];
	$from_pincode = $datapincode[2];
	$msg_sms_pincode = $datapincode[3];
	$answer = send_sms($to_pincode,$from_pincode,$msg_sms_pincode);
}
else if(strpos($messageText,"/hostname2ip") !== false){
	$hostname2ip_de = substr($messageText,13,strlen($messageText));
	$response_hostname2ip_de = get_data_ip("http://tejji.com/ip/url-to-ip-address.aspx?domain=".$hostname2ip_de);
	$DOM = new DOMDocument;
	$DOM->loadHTML($response_hostname2ip_de);
	$items = $DOM->getElementsByTagName('td');
	for ($xb = 0; $xb < $items->length; $xb++){
		if($xb == 1){
			$answer = $items->item($xb)->nodeValue;
			break;
		}
    }
	
}

else{
	$answer = $messageText;

}

$response = [
    'recipient' => [ 'id' => $senderId ],
    'message' => [ 'text' => $answer ]
];
$ch = curl_init('https://graph.facebook.com/v2.7/me/messages?access_token='.$accessToken);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_exec($ch);
curl_close($ch);


// bot by un4