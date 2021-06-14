<?php
namespace App\Common;

class ResultCode extends Enum 
{
    const SUCCESS = 0;                          // 성공
    const FAIL = 1;                             // 실패
    const DB_CONNECTION_ERROR = 99;             // db connection error
    const FAIL_INSERT_PAY_HISTORY = 201;        // pay_history 인서트 실패
    const FAIL_UPDATE_PAY_ID = 211;             // PayId 업데이트 실패
    const FAIL_UPDATE_PAY_STATUS = 212;         // 결제 상태 업데이트 실패
    const FAIL_UPDATE_SMS_SEND = 213;           // sms 발송 플래그 업데이트 실패
    const FAIL_UPDATE_REMAIN_CNT = 215;         // 알림톡 남은 갯수 업데이트 실패
    const FAIL_UPDATE_MONTHLY_SERVICE = 216;    // 알림톡 월간 서비스 적용 실패
    const FAIL_DELETE_PAY_HISTORY = 221;        // pay_history 삭제 실패
    const FAIL_SELECT_PAY_HISTORY = 231;        // pay_history 가져오기 실패
    const FAIL_SELECT_REMAIN_CNT = 232;         // 알림톡 남은 갯수 가져오기 실패
    const NOT_EQUAL_PAY_ID = 241;               // 잘못된 접근, PayId 불일치
    const FAIL_REQUEST_PAY_ID = 301;            // PayId 요청 실패
}