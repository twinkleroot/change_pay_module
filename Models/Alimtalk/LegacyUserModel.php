<?
namespace App\Models\Alimtalk;

use App\Models\Common\BaseEntity;

class LegacyUserModel extends BaseEntity 
{
    protected $tel;
    protected $email;

    public function getTel() 
    {
        return $this->tel;
    }

    public function setTel($tel) 
    {
        $this->tel = $tel;
        return $this;
    }
    
    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

}