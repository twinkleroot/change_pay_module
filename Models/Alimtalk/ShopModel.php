<?
namespace App\Models\Alimtalk;

use App\Models\Common\BaseEntity;

class ShopModel extends BaseEntity 
{

    protected $shopId;
    protected $serviceStart;
    protected $payStart;
    protected $payEnd;
    protected $checkPayEnd;
    protected $newPayEnd;

    public function getShopId() 
    {
        return $this->shopId;
    }

    public function setShopId($shopId) 
    {
        $this->shopId = $shopId;
        return $this;
    }

    public function getServiceStart() 
    {
        return $this->serviceStart;
    }

    public function setServiceStart($serviceStart) 
    {
        $this->serviceStart = $serviceStart;
        return $this;
    }

    public function getPayStart() 
    {
        return $this->payStart;
    }

    public function setPayStart($payStart) 
    {
        $this->payStart = $payStart;
        return $this;
    }

    public function getPayEnd() 
    {
        return $this->payEnd;
    }

    public function setPayEnd($payEnd) 
    {
        $this->payEnd = $payEnd;
        return $this;
    }

    public function getCheckPayEnd() 
    {
        return $this->checkPayEnd;
    }

    public function setCheckPayEnd($checkPayEnd) 
    {
        $this->checkPayEnd = $checkPayEnd;
        return $this;
    }

    public function getNewPayEnd() 
    {
        return $this->newPayEnd;
    }

    public function setNewPayEnd($newPayEnd) 
    {
        $this->newPayEnd = $newPayEnd;
        return $this;
    }
}