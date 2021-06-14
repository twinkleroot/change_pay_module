<?
namespace App\Models\NewAdmin;

class MemberWhiteListModel 
{
    protected $memberNo;
    protected $memberId;
    protected $whitelistIp;
    protected $createDate;

    public function getMemberNo() 
    {
        return $this->memberNo;
    }

    public function setMemberNo($memberNo) 
    {
        $this->memberNo = $memberNo;
        return $this;
    }

    public function getMemberId() 
    {
        return $this->memberId;
    }

    public function setMemberId($memberId) 
    {
        $this->memberId = $memberId;
        return $this;
    }

    public function getWhiteListIp() 
    {
        return $this->whitelistIp;
    }

    public function setWhiteListIp($whitelistIp) 
    {
        $this->whitelistIp = $whitelistIp;
        return $this;
    }

    public function getCreateDate() 
    {
        return $this->createDate;
    }

    public function setCreateDate($createDate) 
    {
        $this->createDate = $createDate;
        return $this;
    }
}
