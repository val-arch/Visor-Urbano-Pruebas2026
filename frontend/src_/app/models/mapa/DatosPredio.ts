export interface DatosPredio  {
    localidad: {
        nombre:any
    },
    colonia: {
        nombre:any,
        codigo_postal:any,
        
    },
    domicilio: any,
    calle: string,
    area_predio: number,
    area_construccion: number,
    denue: [],
    escuelas: [],
    centros_salud: [],
    edificios_gobierno: [],
    url_minimapa: string,
    url_minimapa2?: string,
    url_zip?: string,
    url_uso?: string,
    datosPredio: {},
    cuerpos_agua?,
    vectorial?:any,
};

