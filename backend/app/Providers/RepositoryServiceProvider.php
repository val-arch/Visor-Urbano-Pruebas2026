<?php
namespace App\Providers;

use App\Repositories\CamposRepository;
use App\Repositories\GirosApagadosRepository;
use Illuminate\Support\ServiceProvider;

use App\Repositories\IdentityRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Repositories\RoleRepository;
use App\Repositories\TramiteRepository;
use App\Repositories\SubRoleRepository;
use App\Repositories\UsuarioRepository;
use App\Repositories\NotarioPublicoRepository;
use App\Repositories\MunicipioRepository;
use App\Repositories\RequisitoRepository;
use App\Repositories\ConsultaRequisitosRepository;
use App\Repositories\LogRepository;
use App\Repositories\NotificacionRepository;
use App\Repositories\HistoricoLicenciaRepository;
use App\Repositories\LicenciasRepository;


//Construccion
use App\Repositories\RequisitoConstruccionRepository;
use App\Repositories\CamposConstruccionRepository;
use App\Repositories\ConsultaRequisitosConstruccionRepository;
use App\Repositories\TramiteConstruccionRepository;
use App\Repositories\RoleConstruccionRepository;
use App\Repositories\MunicipioConstruccionRepository;

use App\Repositories\Interfaces\ICampoRepository;
use App\Repositories\Interfaces\IGirosApagadosRepository;
use App\Repositories\Interfaces\IOrderRepository;
use App\Repositories\Interfaces\IProductRepository;
use App\Repositories\Interfaces\IIdentityRepository;
use App\Repositories\Interfaces\IMunicipioRepository;
use App\Repositories\Interfaces\IRoleRepository;
use App\Repositories\Interfaces\ISubRoleRepository;
use App\Repositories\Interfaces\IUsuarioRepository;
use App\Repositories\Interfaces\INotarioPublicoRepository;
use App\Repositories\Interfaces\IRequisitoRepository;
use App\Repositories\Interfaces\INotificacionRepository;
use App\Repositories\Interfaces\IConsultaRequisitosRepository;
use App\Repositories\Interfaces\ILogs;
use App\Repositories\Interfaces\ITramiteRepository;
use App\Repositories\Interfaces\IHistoricoLicenciaRepository;
use App\Repositories\Interfaces\IlicenciaRepository;



//Construccion
use App\Repositories\Interfaces\IRequisitoConstruccionRepository;
use App\Repositories\Interfaces\ICampoConstruccionRepository;
use App\Repositories\Interfaces\IConsultaRequisitosConstruccionRepository;
use App\Repositories\Interfaces\ITramiteConstruccionRepository;
use App\Repositories\Interfaces\IRoleConstruccionRepository;

use App\Repositories\Interfaces\IMunicipioConstruccionRepository;
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(IProductRepository::class, ProductRepository::class);
        $this->app->bind(IOrderRepository::class, OrderRepository::class);
        $this->app->bind(IIdentityRepository::class, IdentityRepository::class);
        $this->app->bind(IRoleRepository::class, RoleRepository::class);
        $this->app->bind(IUsuarioRepository::class, UsuarioRepository::class);
        $this->app->bind(INotarioPublicoRepository::class, NotarioPublicoRepository::class);
        $this->app->bind(ISubRoleRepository::class, SubRoleRepository::class);
        $this->app->bind(IMunicipioRepository::class, MunicipioRepository::class);
        $this->app->bind(IGirosApagadosRepository::class, GirosApagadosRepository::class);
        $this->app->bind(ICampoRepository::class, CamposRepository::class);
        $this->app->bind(IRequisitoRepository::class, RequisitoRepository::class);
        $this->app->bind(IConsultaRequisitosRepository::class, ConsultaRequisitosRepository::class);
        $this->app->bind(ITramiteRepository::class, TramiteRepository::class);
        $this->app->bind(INotificacionRepository::class, NotificacionRepository::class);
        $this->app->bind(ILogs::class, LogRepository::class);
        $this->app->bind(IHistoricoLicenciaRepository::class, HistoricoLicenciaRepository::class);
        $this->app->bind(ILicenciaRepository::class, LicenciasRepository::class);
        $this->app->bind(ITramiteConstruccionRepository::class, TramiteConstruccionRepository::class);


        //////Interfaces and repositorys Construccion

        $this->app->bind(IRequisitoConstruccionRepository::class, RequisitoConstruccionRepository::class);
        $this->app->bind(ICampoConstruccionRepository::class, CamposConstruccionRepository::class);
        $this->app->bind(IConsultaRequisitosConstruccionRepository::class, ConsultaRequisitosConstruccionRepository::class);
        $this->app->bind(IRoleConstruccionRepository::class, RoleConstruccionRepository::class);
        $this->app->bind(IMunicipioConstruccionRepository::class, MunicipioConstruccionRepository::class);
    }
}
