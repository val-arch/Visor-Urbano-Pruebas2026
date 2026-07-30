<?php
namespace App\Repositories\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Log;

interface ILogs
{
     public function paginate(int $take);
    public function find(int $id): ?Log;
}
