<?php
namespace App\Repositories\NewAdmin\Interfaces;

interface MemberWhiteListRepositoryInterface {
    public function getWhiteListIpByMemberId($model);
}