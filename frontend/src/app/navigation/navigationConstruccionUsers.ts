import { FuseNavigation } from '../../@fuse/types';
import { LocalStorageService } from '../shared/services/storage.service';
const _service = new LocalStorageService();

export const navigationConstruccionUser: FuseNavigation[] = [
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
                url: `/mapa/${_service.get('usr').nombre_municipio || ''}`,
                externalUrl: true
            },
            {
                id: 'n-tramite',
                title: 'Nuevo trámite',
                type: 'item',
                url: '/tramite/iniciar-tramite',
                icon: 'add',
                typeIcon: '',
            },
            {
                id: 'tramite',
                title: 'Trámites',
                type: 'item',
                url: '/tramites',
                icon: 'tramites',
                typeIcon: 'custom',
            },
            {
                id: 'historico_visor',
                title: 'Licencias emitidas',
                type: 'item',
                url: '/licencias-emitidas',
                icon: 'history',
                typeIcon: '',
            },
           
        ]
    }
];
