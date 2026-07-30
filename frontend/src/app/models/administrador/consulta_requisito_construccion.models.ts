export class ConsultaRequisitosConstruccion {
   public id:number;
   public folio:string;
   public calle:string;
   public colonia:string;
   public municipio:string;
   public id_municipio: number;
   public codigo_scian:string;
   public nombre_scian:string;
   public superficie_propiedad: number;
   public superficie_actividad: number;
   public nombre_solicitante:string;
   public caracter_solicitante:string;
   public tipo_persona:string;
   public url_minimapa:string;
   public restricciones:string;
   public status: number;
   public anio_folio: number;
   public id_usuario: number;
   constructor(
      info?
      ){

         this.id = info.id || 0;
         this.folio = info.folio || '';
         this.calle = info.calle || '';
         this.colonia = info.colonia || '';
         this.municipio = info.municipio || ''; 
         this.id_municipio = info.id_municipio || 0; 
         this.codigo_scian = info.codigo_scian || '';
         this.nombre_scian = info.nombre_scian || '';
         this.superficie_propiedad = info.superficie_propiedad || 0; 
         this.superficie_actividad = info.superficie_actividad || 0;
         this.nombre_solicitante = info.nombre_solicitante || '';
         this.caracter_solicitante = info.caracter_solicitante || ''; 
         this.tipo_persona = info.tipo_persona || '';
         this.url_minimapa = info.url_minimapa || '';
         this.restricciones = info.restricciones || '';
         this.status = info.status || 0;
         this.anio_folio = info.anio_folio || 0; 
         this.id_usuario = info.id_usuario || 0;
      }
  
}