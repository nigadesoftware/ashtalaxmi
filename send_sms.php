<?php  
header('content-type:text/html;charset=utf-8');

$message  = "Hi Sarang";
//$mobile   ="9960222720";
$mobile   ="7276249056";
$msg = urlencode($message);

//http://46.166.160.57/vendorsms/pushsms.aspx?user=abc&password=xyz&msisdn=919898xxxxxx&sid=SenderId&msg=test%20message&fl=0&gwid=2

$url = "http://sms.vndsms.com/vendorsms/pushsms.aspx?user=SGGBSP&password=abc@123&msisdn=91$mobile&sid=SGGBSP&msg=$msg&fl=0&gwid=2";

$homepage = file_get_contents($url);
if($homepage)
{
  echo "Message Send Compleated...";
}
else{
  echo "Something Went Wrong...";
}

?>