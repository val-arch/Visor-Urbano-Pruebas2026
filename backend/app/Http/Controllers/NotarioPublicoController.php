<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\NotarioPublico;
use App\Repositories\Interfaces\INotarioPublicoRepository;

class NotarioPublicoController extends Controller{

	private INotarioPublicoRepository $notarioPublicoRepository;

	public function __construct(INotarioPublicoRepository $notarioPublicoRepository)
    {
        $this->notarioPublicoRepository = $notarioPublicoRepository;
      
    }

	function getAll(){
		return $this->notarioPublicoRepository->getAll();
	}

}

