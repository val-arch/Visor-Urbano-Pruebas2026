import { FuseNavigation } from '@fuse/types';
import { LocalStorageService } from '../shared/services/storage.service';
const _service = new LocalStorageService();

export const navigation: FuseNavigation[] = [
    {
        id       : 'applications',
        title    : 'Menu',
        translate: 'NAV.APPLICATIONS',
        type     : 'group',
        children : [
           /* {
                id       : 'sample',
                title    : 'Sample',
                translate: 'NAV.SAMPLE.TITLE',
                type     : 'item',
                icon     : 'email',
                url      : '/sample',
                badge    : {
                    title    : '25',
                    translate: 'NAV.SAMPLE.BADGE',
                    bg       : '#F44336',
                    fg       : '#FFFFFF'
                }
            },*/
            {
                id       : 'mapa',
                title    : 'Mapa',
                type     : 'item',
                icon     : 'map',
                typeIcon : '',
                url      : `/mapa/${_service.get('usr').nombre_municipio || '' }`,
                externalUrl:true
            },
            {
                id       : 'n-tramite',
                title    : 'Nuevo trámite',
                type     : 'item',
                url      : '/tramite/iniciar-tramite',
                icon     : 'add',
                typeIcon : '',
            },
            {
                id       : 'tramite',
                title    : 'Trámites',
                type     : 'item',
                url      : '/tramites',
                icon     : 'tramites',
                typeIcon : 'custom',
            },
            {
                id       : 'historico_visor',
                title    : 'Licencias emitidas',
                type     : 'item',
                url      : '/licencias-emitidas',
                icon     : 'history',
                typeIcon : '',
            },
            /*{
                id       : 'historico',
                title    : 'Histórico de licencias',
                type     : 'item',
                url      : '/historico',
                icon     : 'tramites',
                typeIcon : 'custom',
            },*/
            {
                id       : 'reportes',
                title    : 'Reportes',
                type     : 'item',
                url      : '/reportes',
                icon     : 'assessment',
                typeIcon : '',
            },
            {
                id       : 'administrador',
                title    : 'Administrador',
                type     : 'collapsable',
                icon     : 'admin',
                typeIcon : 'custom',
                children : [
                    {
                        id   : 'usuarios',
                        title: 'Usuarios',
                        type : 'item',
                        url  : '/administrador/usuarios'
                    },
                    {
                        id   : 'requisitos',
                        title: 'Requisitos',
                        type : 'item',
                        url  : '/administrador/requisitos'
                    },
                    {
                        id   : 'roles',
                        title: 'Roles',
                        type : 'item',
                        url  : '/administrador/roles'
                    },
                    {
                        id   : 'giros',
                        title: 'Giros',
                        type : 'item',
                        url  : '/administrador/giros'
                    },
                    {
                        id   : 'mi-municipio',
                        title: 'Mi municipio',
                        type : 'item',
                        url  : '/administrador/mi-municipio'
                    },
                    {
                        id   : 'capas-municipio',
                        title: 'Capas municipio',
                        type : 'item',
                        url  : '/administrador/capas-municipio'
                    },
                    // {
                    //     id   : 'impacto',
                    //     title: 'Nivel de impacto',
                    //     type : 'item',
                    //     url  : '/administrador/impacto'
                    // },
                   /* {
                        id   : 'municipios',
                        title: 'Municipios',
                        type : 'item',
                        url  : '/administrador/municipios'
                    }*/
                ]
            },
        ]
    }
];
