import { FuseNavigation } from '../../@fuse/types';
import { LocalStorageService } from '../shared/services/storage.service';
const _service = new LocalStorageService();

export const navigationConstruccion: FuseNavigation[] = [
    {
        id       : 'applications',
        title    : 'Menu',
        translate: 'NAV.APPLICATIONS',
        type     : 'group',
        children : [
          
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
                title    : 'Autorizaciones emitidas',
                type     : 'item',
                url      : '/licencias-emitidas',
                icon     : 'history',
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
                        id   : 'mi-municipio',
                        title: 'Mi municipio',
                        type : 'item',
                        url  : '/administrador/mi-municipio'
                    },
                ]
            },
        ]
    }
];
