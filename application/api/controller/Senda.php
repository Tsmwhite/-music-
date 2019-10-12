<?php
namespace app\api\controller;

use think\Controller;
use think\Db;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Traits\ClientTrait;

use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
class Senda extends Controller
{

    #public function __construct()
    #{
    #    parent::__construct();
        #import('alibabacloud.client.src.AlibabaCloud', EXTEND_PATH, '.php');
        #import('dexterho.fast-paypal.src.Exception.ClientException', EXTEND_PATH, '.php');
        #import('dexterho.fast-paypal.src.Exception.ServerException', EXTEND_PATH, '.php');
    #}

    public function sendSMS($body,$phone) {

        #import('alibabacloud.client.src.Traits.ClientTrait', EXTEND_PATH, '.php');
        #import('alibabacloud.client.src.Traits.DefaultRegionTrait', EXTEND_PATH, '.php');
        #import('alibabacloud.client.src.AlibabaCloud', EXTEND_PATH, '.php');
        #import('dexterho.fast-paypal.src.Exception.ClientException', EXTEND_PATH, '.php');
        #import('dexterho.fast-paypal.src.Exception.ServerException', EXTEND_PATH, '.php');
        #$oo = new ClientTrait();
        #exit;
	     AlibabaCloud::accessKeyClient('LTAIZ2OXDuYTWcC3', 'oDopaAu0APjee1NcoEnO3lyda9FkMb')->regionId('ap-southeast-1') ->asGlobalClient();
          try {
        $result = AlibabaCloud::rpcRequest()
                          ->product('Dysmsapi')
                          ->host('dysmsapi.ap-southeast-1.aliyuncs.com')
                          ->version('2018-05-01')
                          ->action('SendMessageToGlobe')
                          ->method('POST')
                          ->options([
                                        'query' => [
                                            // "To" => "61434265787",
                                            "To" => $phone,

                                            // "From" => "1234567890",
                                            "Message" => "【Snaker Clean】 Your code is".$body,
                                        ],
                                    ])
                          ->request();
              // print_r($result->toArray());
                  $result = array(
                        "code" => 1,
                        "message" => "短信发送成功",
                    );
                  return $result;
          } catch (ClientException $e) {
                  $result = array(
                        "code" => 0,
                        "message" => $e->getErrorMessage() . PHP_EOL,
                    );
                  return $result;
              // echo $e->getErrorMessage() . PHP_EOL;
          } catch (ServerException $e) {
                  $result = array(
                        "code" => 0,
                        "message" => $e->getErrorMessage() . PHP_EOL,
                    );
                  return $result;
              // echo $e->getErrorMessage() . PHP_EOL;
          }



    }


    public function send_email($data,$email){
        try {
            import('PHPMailer.src.PHPMailer', EXTEND_PATH, '.php');
            import('PHPMailer.src.SMTP', EXTEND_PATH, '.php');
            import('PHPMailer.src.Exception', EXTEND_PATH, '.php');
            $mail = new PHPMailer(true);
            //Server settings
            $mail->SMTPDebug = 0;                                       // Enable verbose debug output
            $mail->isSMTP();                                            // Set mailer to use SMTP
            $mail->Host       = 'ssl://smtp.qq.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = '515659688@qq.com';                     // SMTP username
            $mail->Password   = 'idjbpirkmlcabjjc';                               // SMTP password
            #$mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
            $mail->Port       = 465;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom('515659688@qq.com', 'OKJD');
            $mail->addAddress($email);     // Add a recipient
            #$mail->addAddress('ellen@example.com');               // Name is optional
            #$mail->addReplyTo('info@example.com', 'Information');
            #$mail->addCC('cc@example.com');
            #$mail->addBCC('bcc@example.com');

            // Attachments
            #$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            #$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Verification code';
            $mail->Body    = $data;
            $mail->AltBody = $data;

           $mail->send();
        } catch (\Throwable $th) {
            return false;
        }
        return true;
    }





};
