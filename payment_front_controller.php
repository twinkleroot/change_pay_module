<?php
/* 카드 결제 모듈 프론트 컨트롤러*/

header('Content-type: text/html; charset=utf-8');
header('p3p: CP="NOI ADM DEV PSAi COM NAV OUR OTR STP IND DEM"');
mb_internal_encoding('UTF-8');
session_start();

use App\Common\Util;
use App\Common\View;
use App\Common\Logger;
use App\Common\SmsSender;
use App\Common\DiContainer;
use App\Common\Request;
use App\Common\LogLevel;
use App\Common\Constants;
use App\Adapters\LogAdapter;
use App\Adapters\SmsAdapter;
use App\Controllers\PayController;
use App\Factories\DbConnectionFactory;
use App\Factories\PayHistoryFactory;
use App\Repositories\Alimtalk\UserRepository;
use App\Repositories\Alimtalk\PayHistoryRepository;
use App\Repositories\Alimtalk\LegacyUserRepository;
use App\Repositories\Alimtalk\ShopRepository;
use App\Repositories\Alimtalk\UserInfoRepository;
use App\Services\Alimtalk\Payment\RequestPaymentService;
use App\Services\Alimtalk\Payment\CompletePaymentService;
use App\Services\Alimtalk\Payment\CancelPaymentService;
use App\Templates\PaymentTemplate;

// 사용하는 클래스 autoload
require_once($_SERVER["DOCUMENT_ROOT"]. "/app/Common/Autoload.php");

// sample api url
$pgApiUrl = "https://". (Util::IsRealEnv() ? "" : "test-"). "pg.sample.co.kr/api/";
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'nothing';
$result = array();
 
// 결제 App Setting - 순서에 따라 DI
$logger = new Logger(new LogAdapter()); // 어댑터 패턴 : 로거에 로그 기록 객체 주입
$container = (new DiContainer())->setObject('Logger', $logger);
$userRepository = new UserRepository($container);

$container
    ->setObject('PayHistoryRepository', new PayHistoryRepository($container))
    ->setObject('UserRepository', $userRepository)
    ->setObject('LegacyUserRepository', new LegacyUserRepository($container))
    ->setObject('ShopRepository', new ShopRepository($container))
    ->setObject('UserInfoRepository', new UserInfoRepository($container))
    ->setObject('PayHistoryFactory', new PayHistoryFactory())
    ->setObject('DbConnectionFactory', new DbConnectionFactory($userRepository))
    ->setObject('SmsSender', new SmsSender(new SmsAdapter($container)));    // 어댑터 패턴 : 센더에 문자 전송 객체 주입
try {
    $container
        ->setObject('RequestPaymentService', new RequestPaymentService($container, $pgApiUrl))      // 결제 요청 서비스 주입
        ->setObject('CompletePaymentService', new CompletePaymentService($container, $pgApiUrl))    // 결제 완료 처리 서비스 주입
        ->setObject('CancelPaymentService', new CancelPaymentService($container, $pgApiUrl));       // 결제 취소 처리 서비스 주입
} catch (Exception $e) {
    // kibana style
    $logger->log(LogLevel::ERROR(), "[".$_SERVER['SERVER_NAME']."] [". basename(__FILE__). "] [$action] : [". Util::GetUserId(). "] ". $e->getMessage(). $e->getTraceAsString());
    $result['msg'] = '결제 API Connection error';
    $result['code'] = $e->getCode();

    View::ReturnView($result);
}

$serviceType = Constants::ALIMTALK_PAYMENT_SERVICE_TYPE()->__toString();
// 템플릿 패턴 사용
$container->setObject('PaymentTemplate', new PaymentTemplate($container, intval($serviceType)));
try {
    $logger->log(LogLevel::INFO(), "[".$_SERVER['SERVER_NAME']."] [". basename(__FILE__). "] [$action] : [". Util::GetUserId(). "] Before do $action");
    // 컨트롤러에 액션 전달 (라우터대신)
    $result = (new PayController($container, (new Request())->set($_POST), (new Request())->set($_GET)))->action($action);
} catch (Exception $e) {
    $result['msg'] = $e->getMessage() . ' (Error Code:' . $e->getCode() . ')';
    $result['code'] = $e->getCode();
    
    $logger->log(LogLevel::ERROR(), "[".$_SERVER['SERVER_NAME']."] [". basename(__FILE__). "] [$action] : [". Util::GetUserId(). "] ". $result['msg']);
}

View::ReturnView($result);
