<?
namespace App\Repositories\Alimtalk;

use App\Common\DiContainer;
use App\Repositories\Alimtalk\Interfaces\BaseRepositoryInterface;

class BaseRepository implements BaseRepositoryInterface 
{
    protected $db;
    protected $logger;

    public function __construct(DiContainer $container) 
    {
        $this->logger = $container->get('Logger');
    }
    
    public function setDbConnection($db) 
    {
        $this->db = $db;
        return $this;
    }
}