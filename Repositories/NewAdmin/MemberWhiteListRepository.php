<?
namespace App\Repositories\NewAdmin;

use App\Repositories\NewAdmin\Interfaces\MemberWhiteListRepositoryInterface;
use App\Models\NewAdmin\MemberWhiteListModel;

class MemberWhiteListRepository implements MemberWhiteListRepositoryInterface 
{
    protected $db;

    public function __construct($db) 
    {
        $this->db = $db;
    }

    // 회원번호로 화이트리스트 IP 가져오기
    public function getWhiteListIpByMemberId($model) 
    {
        $list = array();
        $memberId = $model->getMemberId();

        $query =
            " select white list ip list query ";
        
        $params = [];

        $results = $this->db->run($query, $params);

        foreach($results as $result) {
            $returnModel = new MemberWhiteListModel();
            $returnModel->setMemberId($memberId);
            $returnModel->setWhiteListIp($result['ip']);
            array_push($list, $returnModel);
            unset($returnModel);
        }

        return $list;
    }
}
