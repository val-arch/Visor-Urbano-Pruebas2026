import { FuseNavigation } from '@fuse/types';

export const navigationTec: FuseNavigation[] = [
    {
        id       : 'applications',
        title    : 'Menu',
        // translate: 'NAV.APPLICATIONS',
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
                url      : '/mapa',
                externalUrl:true
            },
            {
                id       : 'administrador',
                title    : 'Administrador',
                type     : 'collapsable',
                icon     : 'admin',
                typeIcon : 'custom',
                children : [

                    {
                        id   : 'capas-municipio',
                        title: 'Capas municipio',
                        type : 'item',
                        url  : '/administrador/capas-municipio'
                    },
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
