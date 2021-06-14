<?
namespace App\Repositories\NewAdmin;

use App\Models\NewAdmin\IpInfoModel;
use App\Repositories\NewAdmin\Interfaces\IpInfoRepositoryInterface;

class IpInfoRepository implements IpInfoRepositoryInterface 
{
    protected $db;

    public function __construct($db) 
    {
        $this->db = $db;
    }

    // 모든 IP 가져오기
    public function getIpAll() 
    {
        $list = array();

        $query = 
            " select ip all query ";

        $results = $this->db->run($query);

        foreach ($results as $result) {
            $model = new IpInfoModel();
            $model->setIp($result['ip']);
            array_push($list, $model);
            unset($model);
        }
        
        return $list;
    }
}
