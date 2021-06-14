<?php
namespace App\Repositories\Alimtalk\Interfaces;

use App\Repositories\Alimtalk\Interfaces\BaseRepositoryInterface;

interface LegacyUserRepositoryInterface extends BaseRepositoryInterface 
{
    public function getUser($model);
}