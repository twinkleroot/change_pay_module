<?php
namespace App\Common;

use App\Common\ResultCode;

class View 
{
    public static function ReturnView($result) 
    {
        if (isset($result['code'])) {
            // alert 용 메세지로 수정
            if ($result['code'] != ResultCode::SUCCESS()->getValue()) {
                $result['msg'] = '결제에 실패하였습니다. 다시 시도해 주세요.';
            }
        
            // 결제 결과 페이지 호출하고 종료.
            $basePath = '';
            require_once($_SERVER["DOCUMENT_ROOT"] . "/pay_result.php");
            exit;
        }

        echo json_encode($result);
    }
}