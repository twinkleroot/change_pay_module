<?
namespace App\Models\NewAdmin;

class MemberInfoModel 
{
    protected $memberNo;
    protected $memberId;

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
}
