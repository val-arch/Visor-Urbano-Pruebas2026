import { Component, OnInit, TemplateRef, ViewChild } from "@angular/core";
import { GiroService } from "../../../../services/administrador/giros/giro.service";
import { MtxGridColumn } from "@ng-matero/extensions";
import { PageEvent } from "@angular/material/paginator";

@Component({
    templateUrl: "./giro-list.component.html",
    styleUrls: ["./giro-list.component.scss"],
})
export class GiroListComponent implements OnInit {
    @ViewChild("statusTpl", { static: true }) statusTpl: TemplateRef<any>;
    @ViewChild("impacto", { static: true }) impactoTpl: TemplateRef<any>;
    @ViewChild("cedula", { static: true }) cedula_aperturaTpl: TemplateRef<any>;
    columns: MtxGridColumn[] = [];

    list = [];
    total = 0;
    isLoading = true;
    page = 0;
    editId = 0;
    spinner = false;
    query = {
        order: "desc",
        page: 0,
        filter: "",
    };

    date: Date;
    settings: any;

    public items: GiroService[] = [];
    constructor(private giroService: GiroService) {
        this.date = new Date();
    }
    ngOnInit(): void {
        this.columns = [
            { header: "Código", field: "codigo", sortable: false },
            { header: "SCIAN", field: "SCIAN", sortable: false },
            {
                header: "Impacto",
                field: "impacto",
                cellTemplate: this.impactoTpl,
            },
            {
                header: "Cédula de apertura",
                field: "cedula",
                // width:'50px',
                cellTemplate: this.cedula_aperturaTpl,
            },
            {
                header: "Estatus",
                field: "status",
                // width:'50px',
                cellTemplate: this.statusTpl,
            },
        ];
        this.getAll();
    }
    getNextPage(e: PageEvent) {
        this.page = e.pageIndex + 1;
        this.query.page = e.pageIndex;
        this.getAll();
    }
    newContact(): void {}
    applyFilter(e: any) {
        this.query.filter = e;
        this.page = 0;
        this.query.page = 0;
        this.getAll();
    }
    sort(e: any) {
        this.query.order = e;
        this.getAll();
    }
    async onStatus(id_giro: number, id_municipio: number, encendido: number,event) {
    
        // console.log(event.checked);
        if(event.checked){
            encendido =0;
        }else{
            encendido =1;
        }
        // console.log(encendido);
        this.spinner = true;
            this.giroService
                .cedulaConfiguracion(id_giro, id_municipio,encendido)
                .subscribe((res) => {
                    //this.getAll();
                    this.spinner = false;
                });
    }

    async updateImpacto(
        id_giro: number,
        id_municipio: number,
        impacto: number,
        event
    ) {
      this.spinner = true;
      this.giroService.guardarImpacto(id_giro,id_municipio,event.target.value,impacto).subscribe(r=>{
       
        //this.getAll();
        this.spinner = false;
      },e=>{
        console.error(e);
      })
        // console.log(id_giro,id_municipio,impacto,event.target.value);
    }
    async onStatusCedula(
        id_giro: number,
        id_municipio: number,
        encendido: number,
        event
    ) {
        if(event.checked){
            encendido =0;
        }else{
            encendido =1;
        }
        this.spinner = true;
        await this.giroService
        .encenderCedulaConfiguracion(id_giro, id_municipio,encendido)
        .subscribe((res) => {
         // this.getAll();
          this.spinner = false;
        });
        /*
        if (encendido == null) {
            await this.giroService
                .encenderCedulaGiro(id_giro, id_municipio)
                .subscribe((res) => {
                  this.getAll();
                  //this.spinner = false;
                });
        } else {
            await this.giroService
                .apagarCedula(encendido)
                .subscribe((res) => {
                  this.getAll();
                  //this.spinner = false;
                });
        }*/
        // await this.getAll();
        // setTimeout(() => {
        //     //<<<---using ()=> syntax
        //     this.getAll();
        //     this.spinner = false;
        // }, 1000);
    }

    async getAll() {
        this.isLoading = true;
        // this.list = [];
        this.spinner = true;
        await  this.giroService.getAll(this.page, this.query).subscribe(
            (res: any) => {
                this.total = res.total;
                this.list = res.data;
                this.isLoading = false;
                this.spinner = false;
            },
            (error) => {
                this.isLoading = false;
                this.spinner = false;
            }
        );
    }
}
