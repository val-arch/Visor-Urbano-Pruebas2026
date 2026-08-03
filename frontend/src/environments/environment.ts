// This file can be replaced during build by using the `fileReplacements` array.
// `ng build --prod` replaces `environment.ts` with `environment.prod.ts`.
// The list of file replacements can be found in `angular.json`.

export const environment = {
    production: false,
    hmr       : false,
    // MODIFICAMOS ESTA LÍNEA ABAJO (Cambiamos 8000 por 8812), CON FECHA 30 JULIO 2026
    // ES DECIR, QUITAMOS: "SERVER_ORIGIN: 'http://localhost:8000/'," 
    // Y DEJAMOS: "SERVER_ORIGIN: 'http://localhost:8812/',"
    SERVER_ORIGIN: 'http://localhost:8812/',

    
    //SERVER_ORIGIN: 'http://localhost/sistemas_backend/public/',
    SERVER_ORIGIN_GEO_SERVER: 'https://dev-visorurbano.cuernavaca.gob.mx/geoserver',
    SERVER_ORIGIN_MAPA_JALISCO: 'https://mapa.jalisco.gob.mx',
    SERVER_ORIGIN_DJANGO: 'https://dev-visorurbano.cuernavaca.gob.mx/mapa',
    DJANGO_AUTH_TOKEN: '928494f701d9f36e8ff16ce15fd05c27967e8736',
    YEARS_BEFORE: 1,
    YEARS_AFTER: 1
};

/*
 * For easier debugging in development mode, you can import the following file
 * to ignore zone related error stack frames such as `zone.run`, `zoneDelegate.invokeTask`.
 *
 * This import should be commented out in production mode because it will have a negative impact
 * on performance if an error is thrown.
 */
// import 'zone.js/dist/zone-error';  // Included with Angular CLI.