import { Component, OnInit, TemplateRef, ViewChild } from '@angular/core';
import { MunicipioService } from '../../../../services/administrador/municipios/municipio.service';
import { MtxGridColumn } from '@ng-matero/extensions';
import { PageEvent } from '@angular/material/paginator';
import { MatDialog } from '@angular/material/dialog';
import { DialogMunicipioComponent } from '../dialog-municipio/dialog-municipio.component';
import { FormGroup } from '@angular/forms';
import { Router } from '@angular/router';

@Component({
  templateUrl: './municipio-list.component.html',
  styleUrls: ['./municipio-list.component.scss']
})
export class MunicipioListComponent implements OnInit {

  @ViewChild('statusTpl', { static: true }) statusTpl: TemplateRef<any>;
  columns: MtxGridColumn[] = [];


  list = [];
  total = 0;
  isLoading = true;
  page = 0;
  editId=0;
  query = {
    order: 'desc',
    page: 0,
    filter:''
  };
  dialogRef;


  date: Date;
  settings: any;

  public items: MunicipioService[] = [];
  constructor(private municipioService: MunicipioService,  private _matDialog: MatDialog,private router: Router,) {
    this.date = new Date();  
  }
  ngOnInit(): void {

    this.columns = [
      { header: 'Nombre', field: 'nombre', sortable: true},
      { header: 'Director', field: 'director', sortable: true  },
      { header: 'Escudo', field: 'escudo', 
         formatter: (data: any) => `<img src="${data.image ? data.image:'https://odoocdn.com/web/image/res.users/634509/image_128/40x40?unique=248097a'}" alt="${data.nombre}"  width="60" height="60">`, },
      {
        header: 'Acciones',
        field: 'acciones',
        type: 'button',
        width:'200px',
        buttons: [
          { type: 'icon',tooltip:'Editar', color: 'primary', text: 'Editar', icon: 'create' , click:(data)=>this.edtiMunicipio(data) },
        //  { type: 'icon',tooltip:'Dar de baja', color: 'warn', text: 'Baja', icon: 'delete' }
        ],
      }
    ];
    this.getAll();
  }
  getNextPage(e: PageEvent) {
    this.page = e.pageIndex + 1;
    this.query.page = e.pageIndex;
    this.getAll();
  }

  applyFilter(e: any){
    this.page = 0;
    this.query.page = 0;
      this.query.filter = e;
      this.getAll();
  }


  sort(e: any) {
    this.query.order = e;
    this.getAll();
  }
  onStatus(id_giro: number,id_municipio: number,encendido: number) {
  
  }
  
  edtiMunicipio(municipio){

    this.router.navigateByUrl('/administrador/municipio/'+municipio.id)
    return;
    this.dialogRef = this._matDialog.open(DialogMunicipioComponent, {
      panelClass: 'contact-form-dialog',
      data      : {
          user:   municipio,
          action: 'edit'
      }
  });

      this.dialogRef.afterClosed()
          .subscribe(response => {
              if ( !response )
              {
                  return;
              }
              const actionType: string = response[0];
              const formData: FormGroup = response[1];
              switch ( actionType )
              {
                  case 'save':

               
                      break;
               
              }
          });
  }
  
    getAll(): void {
      this.isLoading = true;
      let request = this.municipioService.getAll2(this.page,this.query);
      request.subscribe((res: any) => {
        this.total =res.total;
        this.list = res.data;
        this.isLoading = false;
      },
      error => {
        this.isLoading = false; 
  });
  }  

}
