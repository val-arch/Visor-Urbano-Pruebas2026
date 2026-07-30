<?php
namespace App\Http\Controllers;
use App\DTOs\Usuarios\UsuarioCreateDto;
use App\DTOs\Usuarios\UsuarioRoleUpdateDto;
use App\DTOs\Usuarios\UsuarioUpdateDto;
use App\DTOs\Usuarios\UsuarioMunicipioUpdateDto;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\IUsuarioRepository;
use App\Traits\ApiResponser;
use App\Traits\LogHistorico;
use App\Traits\LogHistoricoConstruccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Models\UserRole;
use App\Models\UserRoleConstruccion;
use App\Models\Usuario;
use App\Repositories\Interfaces\ILogs;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Mail\Roles;
use App\Mail\RolesConstruccion;
use App\Models\MunicipioConstruccion;
use Illuminate\Support\Facades\Mail;


class UsuarioController extends Controller
{
    use ApiResponser;
    use LogHistorico;
    use LogHistoricoConstruccion;
    private IUsuarioRepository $usuarioRepository;

    public function __construct(IUsuarioRepository $usuarioRepository)
    {
        $this->usuarioRepository = $usuarioRepository;

    }
    public function index(Request $request,Request $request2)
    {
        return $this->usuarioRepository->paginate($request->page,$request->filter);
    }
    public function index2(Request $request,Request $request2)
    {
        return $this->usuarioRepository->paginate2($request->page,$request->filter);
    }

    public function index3($value = null, Request $request, Request $request2)
    {
        return $this->usuarioRepository->paginate3($request->page,$request->filter);
    }
    public function index4($value = null, Request $request, Request $request2)
    {
        return $this->usuarioRepository->paginate4($request->page,$request->filter);
    }
    public function show(int $id)
    {
        $result = $this->usuarioRepository->find($id);
        if($result) {
            return $this->successResponse(
                $result,202
            );
        }
        return $this->errorResponse('Product not found', 404);

    }
    public function store($value = null, Request $request)
    {
        // Validation
        $this->validate($request, [
            'name' => 'required'
        ]);
        $store  = new UsuarioCreateDto($request->all());
        if($value!=null){
            $result = $this->usuarioRepository->store($store, $value);
        }else{
            $result = $this->usuarioRepository->store($store, 0);
        }
        $valor_anterior = '';
        $data2          = json_encode($request->except(['token']));
        $this->historial($accion='Se registro un usuario',$valor_anterior,$data2,$tipo=2,0);
        if($result) {
            return $this->successResponse(
                $result,201
            );
        }
        return $this->errorResponse('Ya Existe El Usuario', 409);
    }
    public function update($id,Request $request){
        // Mapping
        $data       = $request->all();
        $data['id'] = $int = (int)$id;
        $entry = new UsuarioUpdateDto($data);

        $usuariosData   = User::where('id', $request->id)->first();
        $valor_anterior = json_encode($usuariosData->getAttributes());
        // Update
        $this->usuarioRepository->update($entry);
        $data2          = json_encode($request->except(['token']));

        $this->historial($accion='Se actualizo el usuario',$valor_anterior,$data2,$tipo=3,0);
        return $this->successResponse(
            $entry,201
        );
    }
    public function updateMunicipio($id,Request $request){
        // Mapping
        $data       = $request->all();
        $data['id'] = $int = (int)$id;
        $entry = new UsuarioMunicipioUpdateDto($data);
        // Update
        $this->usuarioRepository->updateMunicipio($entry);
        return $this->successResponse(
            $entry,202
        );
    }

    public function roleAsiganado($id)
    {
        $result = $this->usuarioRepository->roleAsignado($id);
        if($result) {
            return $this->successResponse(
                $result,202
            );
        }
        return $this->errorResponse('Product not found', 404);
    }
    public function validarToken(Request $request){
        //dd($request->token);
       // dd($request->id);
        $usuario = UserRole::where('user_id', $request->id)->first();
        if( $usuario->token == $request->token ){
            $rolenuevo = $usuario->role_id_pendiente;
            $usuario->role_id_pendiente = null;
            $usuario->role_id = $rolenuevo;
            $usuario->token = null;
            $usuario->role_status = null;
            $usuario->save();
            //return $usuario;
            return redirect('http://visorurbano.cuernavaca.gob.mx/');
        }else{
            return $this->errorResponse('Token Invalido', 405);
        }
    }
    public function validarTokenConstruccion(Request $request){
        //dd($request->token);
       // dd($request->id);
        $usuario = UserRoleConstruccion::where('user_id', $request->id)->first();
        if( $usuario->token == $request->token ){
            $rolenuevo = $usuario->role_id_pendiente;
            $usuario->role_id_pendiente = null;
            $usuario->role_id = $rolenuevo;
            $usuario->token = null;
            $usuario->role_status = null;
            $usuario->save();
            $user = Usuario::where('id', $request->id)->update(['user_type' => 1]);
            //return $usuario;
            return redirect('http://visorurbano.cuernavaca.gob.mx/');
        }else{
            return $this->errorResponse('Token Invalido', 405);
        }
    }
    public function asignarRole($id,Request $request)
    {
        $except         = ['email','id_user','token'];
        $data           = $request->except($except);
        $data2          = json_encode($request->except(['token']));

        $currentUser    = Auth::user();
        $id_municipio   = $currentUser->id_municipio;
        $municipios     = DB::table('municipios')->where('id', $id_municipio)->get();

        $usuariosData   = UserRole::where('user_id', $request->id_user)->first();
        $valor_anterior = $usuariosData->role_id;
        $municipio      = $municipios[0]->nombre;

        $role           = DB::table('roles')->where('id', $request->role_id)->get();
        $role_name      = $role[0]->name;
        $data['id']     = $int = (int)$id;


        $entry          = new UsuarioRoleUpdateDto($data);

        $result         = $this->usuarioRepository->asignarRole( $entry,$id_municipio,$request->token);
        if($result) {
            if($entry->role_id != 1){

                $this->sendMail($request->email,$request->id_user,$request->token,$municipio,$role_name );
            }
            $this->historial($accion='Se actualizo un nuevo role',$valor_anterior,$data2,$tipo=3,0);
            return $this->successResponse(
                'Asiganado Correctamente',202
            );
        }
        $this->historial($accion='Error al actualizar un nuevo role',$valor_anterior,$data2,$tipo=4,0);
        return $this->errorResponse('Error en el Role', 404);
    }

    public function asignarRoleConstruccion($id,Request $request)
    {

        $except         = ['email','id_user','token'];
        $data           = $request->except($except);
        $data2          = json_encode($request->except(['token']));

        $currentUser    = Auth::user();
        $id_municipio   = $currentUser->id_municipio;
        $municipios     = DB::table('municipios_construccion')->where('id', $id_municipio)->get();

        $usuariosData   = UserRoleConstruccion::where('user_id', $request->id_user)->first();
        $valor_anterior = $usuariosData->role_id;
        $municipio      = $municipios[0]->nombre;

        $role           = DB::table('roles_construccion')->where('id', $request->role_id)->get();
        $role_name      = $role[0]->name;
        $data['id']     = $int = (int)$id;

        $entry          = new UsuarioRoleUpdateDto($data);

        $result         = $this->usuarioRepository->asignarRoleConstruccion( $entry,$id_municipio,$request->token);  ;
        if($result) {
            if($entry->role_id != 1){

                $this->sendMailConstruccion($request->email,$request->id_user,$request->token,$municipio,$role_name );
            }
            $this->historialConstruccion($accion='Se actualizo un nuevo role',$valor_anterior,$data2,$tipo=3,0);
            return $this->successResponse(
                'Asiganado Correctamente',202
            );
        }
        $this->historialConstruccion($accion='Error al actualizar un nuevo role',$valor_anterior,$data2,$tipo=4,0);
        return $this->errorResponse('Error en el Role', 404);
    }
    public function asignarSubRole($id)
    {
        $currentUser = Auth::user();
        if(!isset($currentUser->id) || $currentUser->id == null  ){
            return $this->errorResponse('Error en la session', 404);
        }
        $result     = $this->usuarioRepository->asignarSubRole( $currentUser->id,$id);
        if($result) {
            return $this->successResponse(
                'Asiganado Correctamente',202
            );
        }

        return $this->errorResponse('Error en el Role', 404);
    }


    public function asignarRoleMunicipio(Request $request)
    {
           $email = $request->email;
           $data = Usuario::where('email', $email)->first();
           $id_municipio = $request->id_municipio;
           $id_role = $request->id_role;

            if (!empty($data['email'])){

               $query =  DB::table('user_roles')
                                ->join('users', 'users.id', 'user_roles.user_id')
                                ->where('users.email', $email)
                                ->select('user_roles.role_id as role')
                                ->first();

               Usuario::where('email',$email)->update(['id_municipio'=>$id_municipio]);

                $data2 =  array(
                    'created_at' => Carbon::now(),
                    'user_id'    =>  $data['id'],
                    'role_id'    => $id_role
                    );
                    if($query === null){
                        DB::table('user_roles')->insert($data2);
                    }else{
                        DB::table('user_roles')->where('user_id', $data['id'])->update(['role_id' => $id_role, 'updated_at' =>Carbon::now()]);
                    }
               return $this->successResponse(
                'Actualizado Correctamente',202
            );
            }else{
                return $this->errorResponse('Usuario inexistante', 404);
            }
    }


    public function quitarSubRole()
    {
        $currentUser = Auth::user();
        if(!isset($currentUser->id) || $currentUser->id == null  ){
            return $this->errorResponse('Error en la session', 404);
        }
        $result     = $this->usuarioRepository->quitarSubRole( $currentUser->id);
        if($result) {
            return $this->successResponse(
                'Asiganado Correctamente',202
            );
        }
        return $this->errorResponse('Error en el Role', 404);
    }

    public function quitarRole($id)
    {
        $result = $this->usuarioRepository->quitarRole($id);
        return $this->successResponse(
               "Se Eliminó Correctamente",200
        );

    }

    public function quitarRoleConstruccion($id)
    {
        $result = $this->usuarioRepository->quitarRoleConstruccion($id);
        return $this->successResponse(
               "Se Eliminó Correctamente",200
        );

    }

    public function destroy($id)
    {
        $this->usuarioRepository->destroy($id);
        return $this->successResponse(
            'Eliminado Correctamente',204
        );

    }


    public function buscar(Request $request){
        $email = $request->email;
        $idMunicipio = Auth::user()->id_municipio;
        $data = Usuario::where(['email' => $email, 'id_municipio' => $idMunicipio ])->first();
        if($data == false){
            return $this->errorResponse('No tienes permiso', 403);
        }else{
            return $this->successResponse($data);
        }

    }


    public function getUserValidator(Request $request){
        $idMunicipio = Auth::user()->id_municipio;
        $data = MunicipioConstruccion::where(['id_municipio' => $idMunicipio ])->first();
        if($data == false){
            return $this->errorResponse('No tienes permiso', 403);
        }else{
            return $this->successResponse($data);
        }

    }
    public function sendMail($email,$user_id,$token,$municipio,$role_name){

        //Mail::to('victor@visorubano.com')->send(new Roles($token,$user_id,$municipio,$role_name,$email));
        Mail::to($email)->send(new Roles($token,$user_id,$municipio,$role_name,$email));
    }

    public function sendMailConstruccion($email,$user_id,$token,$municipio,$role_name){

        Mail::to($email)->send(new RolesConstruccion($token,$user_id,$municipio,$role_name,$email));
    }


/////////////////////////////////////////////

public function storeConstruccion($value = null, Request $request)
{
    // Validation
    $this->validate($request, [
        'name' => 'required'
    ]);
    $store  = new UsuarioCreateDto($request->all());
    if($value!=null){
        $result = $this->usuarioRepository->storeConstruccion($store, $value);
    }else{
        $result = $this->usuarioRepository->storeConstruccion($store, 0);
    }
    $valor_anterior = '';
    $data2          = json_encode($request->except(['token']));
    $this->historialConstruccion($accion='Se registro un usuario',$valor_anterior,$data2,$tipo=2,0);
    if($result) {
        return $this->successResponse(
            $result,201
        );
    }
    return $this->errorResponse('Ya Existe El Usuario', 409);
}
}
