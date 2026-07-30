import { FuseNavigation } from '../../@fuse/types';
import { LocalStorageService } from '../shared/services/storage.service';
const _service = new LocalStorageService();

export const navigationAdminConstruccion: FuseNavigation[] = [
    {
        id: 'applications',
        title: 'Menu',
        translate: 'NAV.APPLICATIONS',
        type: 'group',
        children: [

            {
                id: 'mapa',
                title: 'Mapa',
                type: 'item',
                icon: 'map',
                typeIcon: '',
                url: `/mapa/`,
                externalUrl: true
            },
            {
                id: 'reportes',
                title: 'Reportes',
                type: 'item',
                url: '/reportes',
                icon: 'assessment',
                typeIcon: '',
            },
            {
                id: 'administrador',
                title: 'Administrador',
                type: 'collapsable',
                icon: 'admin',
                typeIcon: 'custom',
                children: [
                    {
                        id: 'Municipios',
                        title: 'Municipios',
                        type: 'item',
                        url: '/administrador/municipios'
                    },
                    {
                        id: 'usuarios',
                        title: 'Usuarios',
                        type: 'item',
                        url: '/administrador/usuarios'
                    },
                    {
                        id: 'requisitos',
                        title: 'Requisitos',
                        type: 'item',
                        url: '/administrador/requisitos'
                    },
                    {
                        id: 'roles',
                        title: 'Roles',
                        type: 'item',
                        url: '/administrador/roles'
                    },
                    // {
                    //     id   : 'giros',
                    //     title: 'Giros',
                    //     type : 'item',
                    //     url  : '/administrador/giros'
                    // },
                    {
                        id: 'mi-municipio',
                        title: 'Mi municipio',
                        type: 'item',
                        url: '/administrador/mi-municipio'
                    },
                    // {
                    //     id   : 'capas-municipio',
                    //     title: 'Capas municipio',
                    //     type : 'item',
                    //     url  : '/administrador/capas-municipio'
                    // },
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
