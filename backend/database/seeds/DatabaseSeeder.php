<?php


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    //Giros
    // $this->call(RoleSeeder::class);
    // $this->call(UserSeeder::class);
    // $this->call(CampoSeeder::class);
    // $this->call(MunicipioSeeder::class);
    // $this->call(SubRoleSeeder::class);
    // $this->call(RequisitoSeeder::class);
    $this->call(GiroSeederV2::class);
    // $this->call(GiroConfiguracionSeeder::class);
    $this->call(CampoSeederUpdate::class);
    $this->call(UserPruebaSeeder::class);
    $this->call(UserUpdateSeeder::class);

    //Construccion
    $this->call(CampoConstruccionSeeder::class);
    $this->call(MunicipioConstruccionSeeder::class);
    $this->call(RequisitoConstruccionSeeder::class);
    // $this->call(NotariosSeeder::class);

  }
}
