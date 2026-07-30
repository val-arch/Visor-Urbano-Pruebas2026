export class CapaMapa {
    id: number;
    value: string;
    label: string;
    type: string;
    url: string;
    layers: string;
    visible: boolean;
    attribution: string;
    opacity: number;
    format: string;
    owner: number;
    municipality: any;
    editable: boolean;
    projection: string;
    version: string;
    order: number;

    constructor(CapaMapa) {
        this.id = CapaMapa.id || 0;
        this.value = CapaMapa.value || '';
        this.label = CapaMapa.label || '';
        this.type = CapaMapa.type || '';
        this.url = CapaMapa.url || '';
        this.layers = CapaMapa.layers || '';
        this.visible = CapaMapa.visible || true;
        this.attribution = CapaMapa.attribution || '';
        this.opacity = CapaMapa.opacity || 1.0;
        this.format = CapaMapa.format || '';
        this.owner = CapaMapa.owner || 1;
        this.municipality = CapaMapa.municipality || [];
        this.editable = CapaMapa.editable || true;
        this.projection = CapaMapa.projection || 'EPSG:4326';
        this.version = CapaMapa.version || '1.1.1';
        this.order = CapaMapa.order || 2;
    }
}
