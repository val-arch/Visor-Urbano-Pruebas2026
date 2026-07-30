<?php

namespace App\Repositories;

use App\Repositories\Interfaces\INotarioPublicoRepository;
use App\Models\NotarioPublico;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class NotarioPublicoRepository implements INotarioPublicoRepository{
	public function getAll()
    {
		$notarios = NotarioPublico::all();

        return $notarios;
    }
}