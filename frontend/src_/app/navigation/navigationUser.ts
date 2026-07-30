import { FuseNavigation } from '@fuse/types';

export const navigationUser: FuseNavigation[] = [
    {
        id       : 'applications',
        title    : 'Menu',
        translate: 'NAV.APPLICATIONS',
        type     : 'group',
        children : [
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
                title    : 'Mis trámites',
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
        ]
    }
];
