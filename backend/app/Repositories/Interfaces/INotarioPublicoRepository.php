<?php
namespace App\Repositories\Interfaces;

use App\Models\NotarioPublico;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface INotarioPublicoRepository
{
    public function getAll();
}