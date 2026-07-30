
import { Component, OnInit, TemplateRef, ViewChild,EventEmitter, Input,     Inject,
} from "@angular/core";
import { MtxGridColumn } from "@ng-matero/extensions";
import { PageEvent } from "@angular/material/paginator";
import { NotificacionesService} from "../notificaciones.service";
import { MatDialog } from "@angular/material/dialog";
import { NotificacionesDialogComponent } from '../notificaciones-dialog/notificaciones-dialog.component';
import { Router } from '@angular/router';



@Component({
  selector: 'notificaciones-list',
  templateUrl: './list.component.html',
  styleUrls: ['./list.component.scss']
})
export class ListComponent implements OnInit {

  columns: MtxGridColumn[] = [];
  datos: [];
  selectedRow;
  HighlightRow : Number;  

   folio : string;
   municipio : string;
   id : Number;
   estado : Number;
  
    list = [];
    total = 0;
    index = 0;

    isLoading = true;
    page = 0;
    editId = 0;
    query = {
        order: "desc",
        page: 0,
    };
    dialogRef;

    constructor(
        public _listado: NotificacionesService,
        private router: Router,
        public dialog: MatDialog,

    ) {}

    ngOnInit(): void {
        this.columns = [
            {
                header: "Folio",
                field: "folio",
                width: "120px",
            },
            {
                header: "Estatus",
                field:"estado",
                width: "150px",
                formatter: (data) => this.getEstado(data.estado), 
               
            },
            {
                header: "Mensaje",
                field: "mensaje",
                formatter: (data) => {
                    let estado = data.estado;
                    let mensaje = data.mensaje;
                    return `${estado == 1 ? mensaje.slice(0,100) : 'Para ver el contenido da clic aquí'}`;
                }, 
            },
            {   
                width: "150px",
                header: "Fecha",
                field: "fecha",        
                
            },
         
        ];
        
        this.getData();
    };

       /* verNotificacion(row:any) {
            console.log(row) 
            this.folio = row[0].rowData.folio;
            this.municipio = row[0].rowData.municipio;
            this.id =  row[0].rowData.id;
            this.estado = row[0].rowData.estado;
            console.log( "ooooooooo");

            console.log( this.estado);

      
       if(this.estado == 1){
                this.router.navigate([`notificaciones/detalle/${btoa(this.folio)}`]);
            }else{
                const dialogRef = this.dialog.open(NotificacionesDialogComponent, {   
                });
                dialogRef.componentInstance.folio = row[0].rowData.folio;
                dialogRef.componentInstance.municipio = row[0].rowData.municipio;
                dialogRef.componentInstance.id = row[0].rowData.id;
    
            }
            
       }*/

       verNotificacion(row:any) {
        console.log(row) 
        this.folio = row[0].rowData.folio;
        this.municipio = row[0].rowData.municipio;
        this.estado = row[0].rowData.estado;
        this.id =  row[0].rowData.id;
        var n = this.id.toString();
        //console.log("aqui");
        //console.log(this.estado == 1);

        if(this.estado == 1){
            this.router.navigate([`notificaciones/detalle/${btoa(this.folio)}/${btoa(row[0].rowData.tipo)}/${btoa(n)}`]);
        }else{
            const dialogRef = this.dialog.open(NotificacionesDialogComponent, {   
            });
            dialogRef.componentInstance.folio = row[0].rowData.folio;
            dialogRef.componentInstance.municipio = row[0].rowData.municipio;
            dialogRef.componentInstance.id = row[0].rowData.id;
            dialogRef.componentInstance.type = row[0].rowData.tipo;

        }
        
   }


        
    getEstado(data) {
        switch (data) {

            case 1:
                return '<li class="text-success">Notificado</li>';
                break;
              case 0:
                return  '<li class="text-warning">Sin notificar</li>';
                break;
              default:
                break;
        }
        
      }
   
    getNextPage(e: PageEvent) {
        this.page = e.pageIndex + 1;
        this.query.page = e.pageIndex;
        this.getData();
    }

    getData() {
        this.isLoading = true;
        this._listado.getData(this.page).subscribe(
            (res: any) => {
                console.log(res);
                this.total = res.total;
                this.list = res.data.data;
                this.isLoading = false;
            },
            (error) => {
                this.isLoading = false;
                console.log(error);
            }
        );
    }

}

