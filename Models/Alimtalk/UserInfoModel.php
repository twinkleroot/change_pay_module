<?
namespace App\Models\Alimtalk;

use App\Models\Common\BaseEntity;

class UserInfoModel extends BaseEntity 
{
    protected $remainCnt;

    public function getRemainCnt() 
    {
        return $this->remainCnt;
    }

    public function setRemainCnt($remainCnt) 
    {
        $this->remainCnt = $remainCnt;
        return $this;
    }
}