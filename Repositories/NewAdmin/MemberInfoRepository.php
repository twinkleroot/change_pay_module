<?
namespace App\Repositories\NewAdmin;

use App\Repositories\NewAdmin\Interfaces\MemberInfoRepositoryInterface;

class MemberInfoRepository implements MemberInfoRepositoryInterface 
{
    protected $db;

    public function __construct($db) 
    {
        $this->db = $db;
    }

    // 회원 No 가져오기
    public function getMemberNoByMemberId($model) 
    {
        $query = 
            " select user no query ";

        $params = [];

        $result = $this->db->run($query, $params);
        if($result[0]) {
            $model->setMemberNo($result[0]['no']);
        }
        
        return $model;
    }
}
