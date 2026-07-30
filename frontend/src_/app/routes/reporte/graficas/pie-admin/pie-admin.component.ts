import { Component, ElementRef, OnInit, ViewChild } from "@angular/core";
import { PieAdminService } from "./pie-admin.service";
import { FuseUtils } from '@fuse/utils/index'
import { fuseAnimations } from '@fuse/animations';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
@Component({
    selector: "grafica-pie-admin",
    templateUrl: "./pie-admin.component.html",
    styleUrls: ["./pie-admin.component.scss"],
    animations: fuseAnimations,
})
export class PieAdminComponent implements OnInit {
    constructor(public _pie_admin: PieAdminService,private fb: FormBuilder,) {
      
        
    }
    loading: boolean = true;
    loadingAdvanced: boolean = true;
    data = [];
    advancedData = [];
    result;
    view: any[] = [700, 400];
    barD = null;
    act;
    filtros_activos:boolean = false;
    // options
    gradient: boolean = false;
    showLegend: boolean = true;
    showLabels: boolean = true;
    isDoughnut: boolean = false;
    legendPosition: string = "right";
    muni = null;
    fil = null;
    filtro: FormGroup;

    colorScheme = {
        domain: ["#70CE68", "#ABE2F5", "#FFBA38", "#FF4A3B"],
    };
    ngOnInit(): void {
      
      let d = new Date();
      this.filtro = this.fb.group({
        f_inicio: [`${d.getFullYear()}-01-01`, Validators.required],
        f_fin: [`${d.getFullYear()}-12-31`, Validators.required],
        tipo_licencia: ['Todas', Validators.required]});
        this.getData();
        this.getAdvancedpie();
    }
    getAdvancedpie(){
        this._pie_admin.getAdvancedPie().subscribe(
            (resp: any) => {
                console.log(resp);
                if (resp.data) {
                    this.advancedData = resp;
                    this.loadingAdvanced = false;
                }
            },
            (e) => console.error(e)
        );
        
    }
    getData(){
      this.barD = null;
      console.log(this.filtro.value);
      this._pie_admin.getData(this.filtro.value).subscribe(
        (resp: any) => {
            if (resp.data) {
                this.data = resp.data;
                this.loading = false;
            }
        },
        (e) => console.error(e)
    );
    }
    

    async onSelect(data) {
      console.log(data);
        if (!data.extra){
          data = this.data.find( obj => obj.name == data );
          if(!data) return;
        };
        this.act = data;
        this.barD = null;
        await this._pie_admin
            .getDataMunicipio(data.extra,this.filtro.value)
            .toPromise()
            .then((rest: any) => {
                this.barD = rest.data;
                this.muni = data;
                this.fil = this.filtro.value;
            })
            .catch((e) => console.log(e));
        setTimeout(() => {
            let el = document.getElementById("detail_municipio");
            if (el) {
                el.scrollIntoView();
            }
        }, 100);
    }
}
