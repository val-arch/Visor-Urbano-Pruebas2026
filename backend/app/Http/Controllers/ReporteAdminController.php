<?php
namespace App\Http\Controllers;


use App\Traits\ApiResponser;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;



class ReporteAdminController extends Controller{
    use ApiResponser;

    public function getByTypeGiro($tipo = ''){

        $consulta = DB::table('licencias_giro_visor')
        ->where('municipio_id', '<>', 1)
        ->where('tipo_licencia', $tipo)->count();
        return $this->successResponse($consulta);
        
    }
    public function getByTypeHistorico($tipo = ''){

        $consulta = DB::table('historico_licencias_giro')
        ->where('historico_licencias_giro.id_municipio', '<>', 1)
        ->where('historico_licencias_giro.tipo_licencia', $tipo)->count();
        return $this->successResponse($consulta);
        
    }

    public function getByTypeTotal($tipo = ''){

        $count_uno = $this-> getByTypeGiro($tipo);
        $count_dos = $this-> getByTypeHistorico($tipo);
        $total = json_decode($count_uno) + json_decode($count_dos);
        return $this->successResponse($total);
        
    }

    public function getByTypeTotalPlus($tipo = ''){

        $count_uno = $this-> getByTypeGiro('Nueva');
        $count_dos = $this-> getByTypeHistorico('Refrendo');
        $count_tres = $this-> getByTypeGiro('Refrendo');
        $total = json_decode($count_uno) + json_decode($count_dos) + json_decode($count_tres);
        return $this->successResponse($total);
        
    }

    public function main(){

        $count_uno = $this->getByTypeGiro('Nueva');
        $count_uno_aux = $this->getByTypeGiro('Refrendo');
        $count_dos = $this->getByTypeHistorico('Refrendo');
        $count_tres = $this->getByTypeTotal('Refrendo');
        $count_cuatro = $this->getByTypeTotalPlus();
        $data = array('giro'=>$count_uno,'giro_refrendo'=>$count_uno_aux,'historico'=>$count_dos,'total'=>$count_tres,'totalplus'=>$count_cuatro);
        return $this->successResponse($data);
        
    }


    //Construccion


    public function getByTypeConstruccion($tipo = ''){

        $consulta = DB::table('licencias_construccion_visor')
        ->where('municipio_id', '<>', 1)
        ->where('tipo_licencia', $tipo)->count();
        return $this->successResponse($consulta); 
        
    }

    public function getByTypeTotalConstruccion(){

        $count_uno = $this->getByTypeConstruccion('Nueva');
        $count_dos = $this->getByTypeConstruccion('Prorroga');
        $total = json_decode($count_uno) + json_decode($count_dos);
        return $this->successResponse($total);
        
    }

    public function mainConstruccion(){

        $count_uno = $this->getByTypeConstruccion('Nueva');
        $count_uno_aux = $this->getByTypeConstruccion('Prorroga');
        $count_dos = $this->getByTypeTotalConstruccion();
        $data = array('nueva'=>$count_uno,'prorroga'=>$count_uno_aux,'total'=>$count_dos);
        return $this->successResponse($data);
        
    }


}