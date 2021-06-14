<?php
namespace App\Adapters;

use App\Common\DiContainer;
use App\Common\LogLevel;
use App\Common\Util;
use App\Common\ResultCode;
use App\Adapters\Interfaces\SmsAdapterInterface;

class SmsAdapter implements SmsAdapterInterface 
{

    protected $logger;

    public function __construct(DiContainer $container) 
    {
        $this->logger = $container->get('Logger');
    }

    public function sendSMS($par) 
    {
        //check param
        //수신자 번호는 필수
        if( !isset($par['receiver_no']) || strlen($par['receiver_no']) < 1 ) {
            return false;
        }
        if(!isset($par['type'])) { $par['type']=''; }
        if(!isset($par['sender'])) { $par['sender']=''; }
        if(!isset($par['receiver_name'])) { $par['receiver_name']=''; }
        if(!isset($par['content'])) { $par['content']=''; }
        $par['content'] = strip_tags($par['content']);

        return $this->sendBiztalk($par);
	}

    //비즈톡
    private function sendBiztalk($par) 
    {
        //프로세스 <-> 템플릿 매칭
        $template_arr = array(
            "example1" => '10000',
            "example2" => '10001',
            "example3" => '10002',
        );

        //해당 프로세스에 대한 템플릿을 검색한다
        $this_template_id = isset( $template_arr[$par['process']] ) ? $template_arr[$par['process']] : 10003;
        
        //발신대상자들
        $par['receiver_no'] = str_replace("-", "", $par['receiver_no']);
        $par['receiver_no'] = str_replace("|", ",", $par['receiver_no']);
        
        $data = [
            'template_id' => $this_template_id
            ,'message_content' => $par['content']
            ,'original_content' => $par['content']
        ];
        $data['receiver_name'] = isset($par['receiver_name']) ? $par['receiver_name'] : "";
        $data['receiver_no'] = $par['receiver_no'];
        $api_key = "Sample key";
        
        $datas['data'] = $data;
        $datas['isCheckDuplicate'] = false;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.sample.co.kr/api/send");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datas));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json','API-KEY:'.$api_key));
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        $re = curl_exec($ch);
        curl_close($ch);

        $this->logger->log(LogLevel::INFO(), "[".$_SERVER['SERVER_NAME']."] [".Util::GetClassName($this)."] [".__FUNCTION__."] : [".Util::GetUserId()."] Recieved Sending sms api result  : ". $re. " receiver_no : ". $par['receiver_no']);
        
        $result = json_decode($re);
        if($result->code == ResultCode::SUCCESS()->getValue()) {
            return true;
        } else {
            return false;
        }
    }
}
?>
