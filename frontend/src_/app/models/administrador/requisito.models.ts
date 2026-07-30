export class Requisitos {

    public id?: number;
    public name: string;
    public description: string;
    public type: string;
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
}