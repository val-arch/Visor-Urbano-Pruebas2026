import { Component, OnInit, Inject } from '@angular/core';
import { FormBuilder, FormGroup, Validators} from '@angular/forms';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';
import Swal from 'sweetalert2';
import { MunicipioService } from 'app/services/administrador/municipios/municipio.service';

@Component({
  selector: 'app-dialog-add-tipo-tramite',
  templateUrl: './dialog-add-tipo-tramite.component.html',
  styleUrls: ['./dialog-add-tipo-tramite.component.scss']
})
export class DialogAddTipoTramiteComponent implements OnInit {

  btnCss = {
    "background-color": "#003E76",
    color: "white",
  };
  displayedColumns: string[] = ['role', 'orden'];
  default_preguntas = false;

  tipoTramiteForm: FormGroup;
  ordenResolucionForm: FormGroup;
  ordenResolucion:any = [];

  constructor(private _formBuilder: FormBuilder, @Inject(MAT_DIALOG_DATA) public _data: any, private municipioService: MunicipioService, public matDialogRef: MatDialogRef<DialogAddTipoTramiteComponent>) {
    
  }

  ngOnInit(): void {
    this.ordenResolucionForm = this._formBuilder.group({});
    if(this._data.type == 'new'){
      this.tipoTramiteForm = this._formBuilder.group({
        tramite: ['', Validators.required],
        folio_interno: ['', Validators.required],
        default_preguntas : [this.default_preguntas],
        id_municipio: [this._data.id_municipio],
      });
    }else if(this._data.type == 'edit'){
      this.tipoTramiteForm = this._formBuilder.group({
        tramite: [this._data.data.tramite],
        folio_interno: [this._data.data.folio_interno],
        default_preguntas : [this._data.data.default_preguntas],
      });
      this.getOrdenResolucion();
    }
  }

  editTipoTramite(){
    this.municipioService.updateTipoTramite(this._data.data.id, this.tipoTramiteForm.controls['tramite'].value, this.tipoTramiteForm.controls['default_preguntas'].value, this.tipoTramiteForm.controls['folio_interno'].value).subscribe(resp=>{
      Swal.fire({
        title: '¡Éxito!',
        text: '¡Actualizado correctamente!',
        icon: 'success',
        confirmButtonText: 'Ok'
      });
      this.matDialogRef.close();
    });
  }

  getOrdenResolucion(){
    this.municipioService.getOrdenResolucion(this._data.data.id_municipio, this._data.data.id).subscribe(resp=>{
      this.ordenResolucion = resp;
      console.log(this.ordenResolucion);
    });
  }

  changeOrder(id, value, tipo_tramite){
    this.municipioService.updateOrdenResolucion(id, value, tipo_tramite).subscribe(resp=>{
      this.getOrdenResolucion();
    });
  }

  saveTipoTramite(){
    this.municipioService.storeTipoTramite(this.tipoTramiteForm.value).subscribe((result) => {
      Swal.fire({
        title: "¡Éxito!",
        text: "Guardado correctamente!",
        icon: "success",
        confirmButtonText: "Ok",
      });
      this.matDialogRef.close();
    },(error) => {
      Swal.fire({
        title: "Ops",
        text: error.error.error,
        icon: "warning",
        confirmButtonText: "Ok",
      });
                    
    });

  }

}
