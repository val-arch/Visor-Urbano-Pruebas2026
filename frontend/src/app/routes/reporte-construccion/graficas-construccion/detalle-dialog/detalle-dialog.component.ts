import {
  Component,
  ElementRef,
  Inject,
  OnInit,
  ViewChild,
} from "@angular/core";
import {
  FormArray,
  FormBuilder,
  FormControl,
  FormGroup,
  Validators,
} from "@angular/forms";
import {
  MatDialog,
  MatDialogRef,
  MAT_DIALOG_DATA,
} from "@angular/material/dialog";
import { environment } from '@env/environment';
import { MtxGridColumn } from "@ng-matero/extensions";

@Component({
  selector: 'app-detalle-dialog',
  templateUrl: './detalle-dialog.component.html',
  styleUrls: ['./detalle-dialog.component.scss']
})
export class DetalleDialogComponent implements OnInit {

  public action: string;
  public dialogTitle: string;
  columns: MtxGridColumn[] = [];
  list       = [];
  total      = 0;
  isLoading  = true;
  page       = 0;
  editId     =0;
  query      = {
    order: 'desc',
    page : 0,
  };
  
  constructor(
    @Inject(MAT_DIALOG_DATA) private _data: any,
  ) { }

  ngOnInit(): void {
    console.log(this._data);
    this.columns = [
      {
        header: "Folio licencia",
        field: "numero_lic",
        showExpand: true,


        formatter: (data) => {
          let d = data.numero_lic.toString();
          return d.padStart(5, "0");
        }
      },
      {
        header: "Tipo de licencia",
        field: "tipo_licencia"
      },
      { header :'Actividad comercial',
        field  : 'nombre_scian'
      },
      { header :'Dueño',
        field  : 'dueno'
      },
      {
        header :'Acciones',
        field  :'acciones',
        type   :'button',
        buttons: [
          { type: 'icon',tooltip:'Ver licencia', color: 'accent', text: 'Ver licencia', icon: 'picture_as_pdf' , click:(data)=>this.verPdf(data)},
        //  { type: 'icon',tooltip:'Dar de baja', color: 'warn', text: 'Baja', icon: 'delete' }
        ],
      } 
    ];
    this.setData();
  }

  setData(){
    
    this.list = this._data.data.data;
    this.total = this._data.data.data.length;
    this.isLoading = false;
    console.log(this.list);
  }

  verPdf(data){
    window.open(`${environment.SERVER_ORIGIN}licenciaConstruccionById/${btoa(data.id)}`,'_blank')
  }

}


// <a mat-icon-button color="accent" target="_blank"
//         href="{{server}}licenciaGiro/{{btoaf(row.folio)}}/{{btoaf(row.anio_licencia)}}" matTooltip="Ver licencia">
//         <mat-icon>picture_as_pdf</mat-icon>
//     </a>
// environment.SERVER_ORIGIN;
