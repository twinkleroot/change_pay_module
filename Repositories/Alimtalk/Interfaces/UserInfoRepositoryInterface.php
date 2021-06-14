<?php
namespace App\Repositories\Alimtalk\Interfaces;

use App\Models\Alimtalk\PayHistoryModel;
use App\Repositories\Alimtalk\Interfaces\BaseRepositoryInterface;

interface UserInfoRepositoryInterface extends BaseRepositoryInterface 
{
    public function updateRemainCount(PayHistoryModel $payHistory);
    public function getRemainCount();
}