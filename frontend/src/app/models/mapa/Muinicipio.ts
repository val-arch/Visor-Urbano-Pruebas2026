export interface Municipio {
    id: number;
    nombre: string;
    cve_ent?: string;
    cve_mun?: string;
    cvegeo?: string;
    tiene_zonificacion: boolean;
}