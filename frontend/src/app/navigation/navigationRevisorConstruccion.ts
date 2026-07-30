import { FuseNavigation } from '@fuse/types';

export const navigationRevisorConstruccion: FuseNavigation[] = [
    {
        id       : 'applications',
        title    : 'Menu',
        translate: 'NAV.APPLICATIONS',
        type     : 'group',
        children : [
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
            {
                id       : 'mapa',
                title    : 'Mapa',
                type     : 'item',
                icon     : 'map',
                typeIcon : '',
                url      : '/mapa',
                externalUrl:true
            },
        ]
    }
];
