import {Injectable} from "@angular/core";
import {HttpClient, HttpHeaders} from "@angular/common/http";
import {CapaMapa} from "app/models/mapa/CapaMapa";
import {MapaBase} from "app/models/mapa/MapaBase";
import {Municipio} from "app/models/mapa/Muinicipio";
import {environment} from "@env/environment";
import {map} from "rxjs/operators";
import { Observable } from 'rxjs';

@Injectable({
    providedIn: "root",
})
export class MapaService {
    constructor(private httpClient: HttpClient) {
    }

    getMunicipios(): any {
        return this.httpClient.get<Municipio[]>(
            `${environment.SERVER_ORIGIN_DJANGO}/rest/v1/municipios/`
        );
    }

    getGeomMunicipio(id: number): any {
        return this.httpClient.get(
            `${environment.SERVER_ORIGIN_DJANGO}/rest/v1/municipios-geom/${id}/`
        )
    }

    getImpactoMunicipio(municipio_id: number): any {
        const headers = new HttpHeaders()
        headers.append("Access-Control-Allow-Origin", "*")
        return this.httpClient.get(
            `${environment.SERVER_ORIGIN_GEO_SERVER}/ows?service=WFS&request=GetFeature&version=2.0.0&typename=VUJ:denue&outputFormat=application/json&cql_filter=municipio_id=${municipio_id}`
        )
    }

    getCapasMunicipio(value: number): any {
        return this.httpClient.get<CapaMapa[]>(
            `${environment.SERVER_ORIGIN_DJANGO}/rest/v1/capas-mapa/?municipality=${value}`
        );
    }

    getCoordenadasPredioArchivo(data: any): any {
        return this.httpClient.post<any>(
            `${environment.SERVER_ORIGIN_DJANGO}/rest/v1/subir-vectores-predio/`,
            data,
            {}
        );
    }


    getWFSFeature(url: string): any {
        const headers = new HttpHeaders();
        headers.append("Access-Control-Allow-Origin", "*");
        return this.httpClient.get(url);
    }

    getGoogleMapsGeo(url: string): any {
        return this.httpClient.get(url);
    }

    getRequisitosMunicipio(municipio) {
        return this.httpClient.get<any>(
            `${environment.SERVER_ORIGIN}campos_public/campo_requisito_tramite/${municipio}`
        );
    }

    getPreguntasSioNo(municipio, id) {
        return this.httpClient.get<any>(
            `${environment.SERVER_ORIGIN}campos_public/get_pregunta_si_o_no/${municipio}/${id}`
        );
    }

    getRequisitosMunicipioConstruccion(municipio) {
        return this.httpClient.get<any>(
            `${environment.SERVER_ORIGIN}campos_public/campo_requisito_tramite_construccion/${municipio}`
        );
    }

    getRequisitosMunicipioConstruccion2(municipio, id) {
        return this.httpClient.get<any>(
            `${environment.SERVER_ORIGIN}campos_public/campo_requisito_tramite_construccion/${municipio}/${id}`
        );
    }

    guardarGiroTramiteHistorico(data) {
        console.log("guardarGiroTramiteHistorico", data)
        if (data.id) {
            let datos = {
                giro: data.giro
            };
            return this.httpClient.patch<any>(
                `${environment.SERVER_ORIGIN_DJANGO}/rest/v1/registros-tramite/${data.id}/`,
                datos,
                {
                    headers: {
                        Authorization: `Token ${environment.DJANGO_AUTH_TOKEN}`,
                    },
                }
            );
        }
    }

    guardarRegistroTramiteHistorico(data) {
        console.log("guardarRegistroTramiteHistorico", data)
        if (data.id) {
            let datos = {
                id_historico: data.id_historico,
                folio: data.folio,
                area: 0,
                tipo_tramite: 'refrendo',
                origen_tramite: 'importado',
                bbox: data.bbox,
                municipio: data.municipio,
                geom: {"type": "Polygon", "coordinates": data.geom}

            };
            return this.httpClient.patch<any>(
                `${environment.SERVER_ORIGIN_DJANGO}/rest/v1/registros-tramite/${data.id}/`,
                datos,
                {
                    headers: {
                        Authorization: `Token ${environment.DJANGO_AUTH_TOKEN}`,
                    },
                }
            );
        }
        let datosnuevos = {
            id_historico: data.id_historico,
            folio: data.folio,
            area: 0,
            tipo_tramite: 'refrendo',
            origen_tramite: 'importado',
            bbox: data.bbox,
            municipio: data.municipio,
            geom: {"type": "Polygon", "coordinates": data.geom}
        }
        console.log("datosnuevos", datosnuevos)
        return this.httpClient.post<any>(
            `${environment.SERVER_ORIGIN_DJANGO}/rest/v1/registros-tramite/`,
            datosnuevos,
            {
                headers: {
                    Authorization: `Token ${environment.DJANGO_AUTH_TOKEN}`,
                },
            }
        );
    }

    guardarRegistroTramite(data) {
        if (data.id) {
            let d = {
                properties: {
                    folio: data.folio,
                    giro: data.giro,
                    area: data.area,
                    municipio: data.municipio_id
                },
            };
            return this.httpClient.patch<any>(
                `${environment.SERVER_ORIGIN_DJANGO}/rest/v1/registros-tramite-geom/${data.id}/`,
                d,
                {
                    headers: {
                        Authorization: `Token ${environment.DJANGO_AUTH_TOKEN}`,
                    },
                }
            );
        }
        return this.httpClient.post<any>(
            `${environment.SERVER_ORIGIN_DJANGO}/rest/v1/registros-tramite-geom/`,
            data,
            {
                headers: {
                    Authorization: `Token ${environment.DJANGO_AUTH_TOKEN}`,
                },
            }
        );
    }

    nuevoNivelImpacto(data) {
        return this.httpClient.post<any>(
            `${environment.SERVER_ORIGIN_DJANGO}/rest/v1/nivel-impacto/`,
            data,
            {
                headers: {
                    Authorization: `Token ${environment.DJANGO_AUTH_TOKEN}`,
                },
            }
        );
    }

    actualizarNivelImpacto(data) {
        return this.httpClient.put<any>(
            `${environment.SERVER_ORIGIN_DJANGO}/rest/v1/nivel-impacto/${data.id}/`,
            data,
            {
                headers: {
                    Authorization: `Token ${environment.DJANGO_AUTH_TOKEN}`,
                },
            }
        );
    }

    agregarCapaMunicipio(data) {
        return this.httpClient.post<any>(
            `${environment.SERVER_ORIGIN_DJANGO}/rest/v1/capas-mapa/`,
            data,
            {
                headers: {
                    Authorization: `Token ${environment.DJANGO_AUTH_TOKEN}`,
                },
            }
        );
    }

    getWMSCapabilities(url) {
        // @ts-ignore
        return this.httpClient.get<any>(url, {responseType: "text"});
    }

    actualizarCapaMunicipio(data) {
        return this.httpClient.put<any>(
            `${environment.SERVER_ORIGIN_DJANGO}/rest/v1/capas-mapa/${data.id}/`,
            data,
            {
                headers: {
                    Authorization: `Token ${environment.DJANGO_AUTH_TOKEN}`,
                },
            }
        );
    }

    eliminarNivelimpacto(id) {
        return this.httpClient.delete<any>(
            `${environment.SERVER_ORIGIN_DJANGO}/rest/v1/nivel-impacto/${id}/`,
            {
                headers: {
                    Authorization: `Token ${environment.DJANGO_AUTH_TOKEN}`,
                },
            }
        );
    }

    eliminarCapaMunicipio(id) {
        return this.httpClient.delete<any>(
            `${environment.SERVER_ORIGIN_DJANGO}/rest/v1/capas-mapa/${id}/`,
            {
                headers: {
                    Authorization: `Token ${environment.DJANGO_AUTH_TOKEN}`,
                },
            }
        );
    }

    consultaRequisitos(data) {
        return this.httpClient.post<any>(
            `${environment.SERVER_ORIGIN}consulta_requisitos/requisitos`,
            data,
            {headers: {"Content-Type": "application/json"}}
        );
    }

    consultaRequisitosConstruccion(data) {
        return this.httpClient.post<any>(
            `${environment.SERVER_ORIGIN}consulta_requisitosConstruccion/requisitos`,
            data,
            {headers: {"Content-Type": "application/json"}}
        );
    }

    getGirosMunicipio(municipio) {
        return this.httpClient
            .get<any>(
                `${environment.SERVER_ORIGIN}giros_public/getEncendidos?municipio_id=${municipio}`
            )
            .pipe(
                map((data) => {
                    return data.data;
                })
            );
    }

    getGirosMunicipioAll(municipio) {
        return this.httpClient
            .get<any>(
                `${environment.SERVER_ORIGIN}giros_public/getAll?municipio_id=${municipio}`
            )
            .pipe(
                map((data) => {
                    return data.data;
                })
            );
    }

    getGirosMunicipios() {
        return this.httpClient
            .get<any>(
                `${environment.SERVER_ORIGIN}giros_public/getEncendidos`
            )
            .pipe(
                map((data) => {
                    return data.data;
                })
            );
    }

    registrarFicha(data) {

        return this.httpClient.post(
            `${environment.SERVER_ORIGIN}/ficha_tecnica`,
            data
        );

    }

    getGeocode(domicilio: string, municipio: string): Observable<any> {
        return this.httpClient.get(`${environment.SERVER_ORIGIN}/geocode`, {
          params: {
            domicilio: domicilio,
            municipio: municipio
          }
        });
      }

      getReverseGeocode(lat: number, lng: number): Observable<any> {
        return this.httpClient.get(`${environment.SERVER_ORIGIN}/reverse-geocode`, {
          params: {
            lat: lat.toString(),
            lng: lng.toString()
          }
        });
      }

    sendToLaravelAPI(properties: any,coordenadas:any): any {
        // Puedes definir la URL de tu API de Laravel en el archivo environment para un manejo más limpio
        return this.httpClient.get(`${environment.SERVER_ORIGIN}ficha_semadet/${properties[0]['id']}`);
      }

}
