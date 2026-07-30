export class RequisitosConstruccion {

    public id?: number;
    public name: string;
    public description: string;
    public type: string;
    public tramite_relacionado: number;
    public description_rec: string;
    public fundamento: string="";
    public opciones: [];
    public opciones_desc: string;
    public step: string;
    public secuencia: number;
    public requerido: number;
    public condicion_visible: string;
    public campo_afectado: string;
    public tipo_tramite: string;
    public condicion_giro?: string;
    public condicion_dependencia?: string;
    public info_licencia?: string;


    public actividad_condicion :string;
    public actividad_metros :number;
    public construccion_total_metros_condicion :number;
    public construccion_total_metros_value :number;
    public demolicion_metros_condicion :string;
    public demolicion_metros_value :number;
    public viviendas_construccion_condicion :string;
    public viviendas_construccion_value :number;
    public nivel_nuevo_construccion_condicion :string;
    public nivel_nuevo_construccion_value :number;
    public sotano_nuevo_construccion_condicion :string;
    public sotano_nuevo_construccion_value :number;
    public nivel_superior_planta_baja_condicion :string;
    public todasCondiciones :string;
    public pregunta_si_o_no_condicion:string;
    public pregunta_si_o_no_value: string;


}