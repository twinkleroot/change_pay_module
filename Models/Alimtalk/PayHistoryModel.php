<?
namespace App\Models\Alimtalk;

use App\Models\Common\BaseEntity;

class PayHistoryModel extends BaseEntity 
{

    protected $module;
    protected $payId;
    protected $userId;
    protected $settingPrice;
    protected $orderQty;
    protected $totalFee;
    protected $payType;
    protected $monthlyPrice;
    protected $confirmDate;
    protected $type;

    public function __toString() 
    {
        return json_encode(array(
            'module' => $this->module,
            'payId' => $this->payId,
            'userId' => $this->userId,
            'settingPrice' => $this->settingPrice,
            'orderQty' => $this->orderQty,
            'totalPrice' => $this->totalPrice,
            'payType' => $this->payType,
            'monthlyPrice' => $this->monthlyPrice,
            'confirmDate' => $this->confirmDate,
            'type' => $this->type,
        ));
    }

    public function getModule() 
    {
        return $this->module;
    }

    public function setModule($module) 
    {
        $this->module = $module;
        return $this;
    }

    public function getPayId()
    {
        return $this->payId;
    }

    public function setPayId($payId)
    {
        $this->payId = $payId;
        return $this;
    }

    public function getUserId() 
    {
        return $this->userId;
    }

    public function setUserId($userId) 
    {
        $this->userId = $userId;
        return $this;
    }

    public function getSettingPrice()
    {
        return $this->settingPrice;
    }

    public function setSettingPrice($settingPrice)
    {
        $this->settingPrice = $settingPrice;
        return $this;
    }

    public function getOrderQty()
    {
        return $this->orderQty;
    }

    public function setOrderQty($orderQty)
    {
        $this->orderQty = $orderQty;
        return $this;
    }

    public function getTotalPrice()
    {
        return $this->totalPrice;
    }

    public function setTotalPrice($totalPrice)
    {
        $this->totalPrice = $totalPrice;
        return $this;
    }

    public function getPayType()
    {
        return $this->payType;
    }

    public function setPayType($payType)
    {
        $this->payType = $payType;
        return $this;
    }

    public function getMonthlyPrice()
    {
        return $this->monthlyPrice;
    }

    public function setMonthlyPrice($monthlyPrice)
    {
        $this->monthlyPrice = $monthlyPrice;
        return $this;
    }
    
    public function getConfirmDate()
    {
        return $this->confirmDate;
    }

    public function setConfirmDate($confirmDate)
    {
        $this->confirmDate = $confirmDate;
        return $this;
    }
    
    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

}