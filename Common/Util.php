<?php
namespace App\Common;

class Util 
{
    /**
     * check env
     *
     * @return bool 리얼환경인지 아닌지 여부
     */
    public static function IsRealEnv() 
    {
        // is_real_env() - 레거시의 라이브러리 함수 호출
        return is_real_env();
    }

    /**
     * convert snake_case to camelCase
     *
     * @param string $input 변환할 문자열
     * @param string $separator 구분자
     * @return string 변환한 문자열
     */
    public static function Camelize($input, $separator = '_')
    {
        return lcfirst(str_replace($separator, '', ucwords($input, $separator)));
    }

    /**
     * IP 주소 마스킹 (ex : 192.168.***.***)
     *
     * @param string $ip 변환해야할 ip 주소
     * @return string 마스킹된 ip 주소
     */
    public static function MaskingIp($ip) 
    {
        $ips = explode(".", $ip);

        return sprintf("%s.%s.***.***", $ips[0], $ips[1]);
    }

    /**
     * lib/config.php 참조. $user_id 가져오는 부분만 따왔다.
     *
     * @return string 회원 루나 id
     */
    public static function GetUserId() 
    {
        $userId = isset($_SESSION['id']) ? $_SESSION['id'] : '';
        if(isset($_REQUEST['userId']) && isset($_REQUEST['adminUser']) && isset($_REQUEST['otp'])) {
            $userId = $_REQUEST['userId'];
        } else if(isset($_SESSION['userId'])) {
            $userId = $_SESSION['userId'];
        }
        return $userId;
    }

    /**
     * 네임스페이스를 제외한 클래스 이름을 가져온다.
     *
     * @return string class name
     */
    public static function GetClassName($obj)
    {
        return (new \ReflectionClass($obj))->getShortName();
    }

}