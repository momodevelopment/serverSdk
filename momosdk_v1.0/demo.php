<?php

//Created By Lanh.Luu
//Server Sdk versions 1.0
//Reference https://github.com/momodevelopment
//Date: Mar 27, 2017

include('Crypt/RSA.php');//Key size 2048 bits

//Public key test provided by MoMo. Please contact MoMo team for getting  Public key Production
$publickey = "-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAkpa+qMXS6O11x7jBGo9W3yxeHEsAdyDE40UoXhoQf9K6attSIclTZMEGfq6gmJm2BogVJtPkjvri5/j9mBntA8qKMzzanSQaBEbr8FyByHnf226dsLt1RbJSMLjCd3UC1n0Yq8KKvfHhvmvVbGcWfpgfo7iQTVmL0r1eQxzgnSq31EL1yYNMuaZjpHmQuT24Hmxl9W9enRtJyVTUhwKhtjOSOsR03sMnsckpFT9pn1/V9BE2Kf3rFGqc6JukXkqK6ZW9mtmGLSq3K+JRRq2w8PVmcbcvTr/adW4EL2yc1qk9Ec4HtiDhtSYd6/ov8xLVkKAQjLVt7Ex3/agRPfPrNwIDAQAB
-----END PUBLIC KEY-----";
//Key size 2048 bits

function execPostRequest($url, $data)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}


 $api_payment = "http://apptest2.momo.vn:8091/paygamebill";
 //ID test provided by MoMo. Please contact MoMo team for getting MerchantCode, Public key Production
 $partner_code = "SCB01";
 //ipaddress is your server ip address
 $ipaddress    = "192.168.56.102";
 //phonenumber wallet get from app MoMo
 $phonenumber  = "0919888999"; //Client send to Server
 //Token get from app MoMo, Client send to Server
 $tokendata    = "v2/CgSRv8qoeWfhrTI6f9n+PhFf7XxeWrs9CNjyZfIRQhvxdA11csghkKW7prwBYeS0Ot1w8LPeDI9CftgSRrZJYQ3qUH0zPwZJOwOgXpTvXL/2H3pT6Z7DXD2jfUJteLt2B+lo731slGXZapmnsVCONGPAJtbQJLsKhGIWfLrxM0eUUDbT1b6cE/8BNa7eISOSWsrug9IEOXxhPWi0VpTq5CxsqzP6adcFjKBugtE8vhRZ2+WSOMOitNOW33nqAp/KGqGiJb69yK8ux0EUToBv4GFRIGZht79gSGJNTUShd9IK2vVU7W55/g6K25ywPiPhuzyCyoHf1nSPI4bNMEbTWPUcwU9M62v9xpoWtrKiptaKFUbn3GONnddKLytpBEb+gUbtoIQupUoCyuXyFHVoJ4QgDCl7Xcb1PoBcSlxgWT1/wUmEPEvH4j0sOvU28tnc";

 $your_transactionId_init = "";
 $your_billId_init = "";

$rsa = new Crypt_RSA();
$rsa->loadKey($publickey); // public key

//IMPORTANT: build hash parameter
//MUST BE CHANGE the phonenumber,transid, billid, amount from plain text bellow FOR YOUR BUSSINESS
$plaintext = "{\"phonenumber\":\"0919888999\",\"amount\":100000,\"fee\":0,\"merchantcode\":\"SCB01\",\"transid\":\"41129898989\",\"username\":\"usernameOrNicknameOrEmail_optional\",\"billid\":\" bill2015002102\",\"version\":\"1.0\"}";
echo "RSA Plain text: ".$plaintext."\n";

//Lúc triển khai

//set mode
$rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
$ciphertext = $rsa->encrypt($plaintext);

//ciphertext
echo "RSA Cipher text: ". $ciphertext."\n";

//decrypt (no need, only debug)
//echo "Decrypt ciphertext: ". $rsa->decrypt($ciphertext)."\n";
echo "Start payment:\n";


   //Body post to Server MoMo include: //{"merchantcode":"","ipaddress":"","phonenumber":"","data":"","hash":""}
   $postData = "merchantcode=".$partner_code."&data=".$tokendata."&hash=".$ciphertext."&ipaddress=".$ipaddress."&phonenumber=".$phonenumber;
   //
   //
   $payment_result = execPostRequest($api_payment, $postData);

   $print_result=json_decode($payment_result,true);  // decode json
   echo "Payment_result: ". $payment_result ."\n";
?>
