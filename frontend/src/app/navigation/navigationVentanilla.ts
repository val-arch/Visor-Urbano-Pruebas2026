import { FuseNavigation } from '@fuse/types';

export const navigationVentanilla: FuseNavigation[] = [
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
                id       : 'notificaciones',
                title    : 'Notificaciones',
                type     : 'item',
                url      : '/notificaciones/list',
                icon     : 'email',
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
