<?php
namespace App\Repositories\Interfaces;


use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\Paginator;
use App\Models\Municipio;
use Illuminate\Pagination\LengthAwarePaginator;

interface INotificacionRepository
{
    /**
     * @param string $id
     * @param $file
     */
    public function image($id, $file): void;
    public function destroy(int $id): void;
}
