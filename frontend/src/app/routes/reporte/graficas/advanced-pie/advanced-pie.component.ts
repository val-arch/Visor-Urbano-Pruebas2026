import { Component, Input, OnInit } from "@angular/core";
import { AdvancedPieService } from "./advanced-pie.service";

@Component({
    selector: "grafica-advanced-pie",
    templateUrl: "./advanced-pie.component.html",
    styleUrls: ["./advanced-pie.component.scss"],
})
export class AdvancedPieComponent implements OnInit {
    values = [
        {
            name: "Consulta de requisitos",
            value: 0,
        },
        {
            name: "Inicio de trámite",
            value: 0,
        },
        {
            name: "Trámites en revisión",
            value: 0,
        },
        {
            name: "Licencias emitidas",
            value: 0,
        },
    ];
    result;
    loading: boolean = true;
    gradient: boolean = false;
    showLegend: boolean = true;
    showLabels: boolean = true;
    isDoughnut: boolean = true;

    @Input() extra = null;
    @Input() muni = null;
    @Input() fil = null;

    colorScheme = {
        domain: ["#878E90", "#ABE2F5", "#FFD138", "#003E76"],
    };

    constructor(public _advancedService: AdvancedPieService) {}

    ngOnInit(): void {
        if (this.extra == null) {
            this._advancedService.advancedPie().subscribe(
                (rest: any) => {
                    this.result = rest;
                    if (rest.data) {
                        const {
                            consulta,
                            emitidas,
                            inicio,
                            revision,
                        } = rest.data;
                        this.values[0].value = consulta;
                        this.values[1].value = inicio;
                        this.values[2].value = revision;
                        this.values[3].value = emitidas;
                        this.loading = false;
                    }
                },
                (e) => {}
            );
        } else {
            // console.log(this.extra);
            const { consulta, emitidas, inicio, revision } = this.extra.data;
            this.values[0].value = consulta;
            this.values[1].value = inicio;
            this.values[2].value = revision;
            this.values[3].value = emitidas;
            this.loading = false;
        }
    }

    onSelect(data): void {
        // console.log("Item clicked", JSON.parse(JSON.stringify(data)));
    }

    onActivate(data): void {
        // console.log('Activate', JSON.parse(JSON.stringify(data)));
    }

    onDeactivate(data): void {
        // console.log('Deactivate', JSON.parse(JSON.stringify(data)));
    }
}
