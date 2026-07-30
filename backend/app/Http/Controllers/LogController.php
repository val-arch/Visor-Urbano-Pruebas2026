<?php
namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ILogs;
use App\Traits\ApiResponser;

class LogController extends Controller
{
    use ApiResponser;
    private ILogs $logRepository;

    public function __construct(ILogs $logRepository)
    {
        $this->logRepository = $logRepository;
    }

    public function index($tipo)
    {
        return $this->logRepository->paginate($tipo);
    }

    public function show(int $id)
    {
        $result = $this->logRepository->find($id);
        if($result) {
            return $result;
        }
        return $this->errorResponse('Producto No Encotrado', 404);      
    }
}
