import { Component, OnInit, Inject } from '@angular/core';
import { MunicipioService } from 'app/services/administrador/municipios/municipio.service';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';
import { FormBuilder, FormGroup, FormControl, Validators } from '@angular/forms';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-dialog-resolutivo',
  templateUrl: './dialog-resolutivo.component.html',
  styleUrls: ['./dialog-resolutivo.component.scss']
})
export class DialogResolutivoComponent implements OnInit {


  campos:any = [];
  respuestas:any = [];
  folio;
  btnCss = {
    "background-color": "#003E76",
    color: "white",
  };
  btnCss2 = {
    "background-color": "GRAY",
    color: "white",
  };
  btnCss3 = {
    "background-color": "ORANGE",
    color: "white",
  };

  camposForm: FormGroup;

  constructor(private municipioService: MunicipioService, @Inject(MAT_DIALOG_DATA) public _data: any, private _formBuilder: FormBuilder, public matDialogRef: MatDialogRef<DialogResolutivoComponent>) { }

  ngOnInit(): void {
    this.folio = this._data.folio_interno;
    this.camposForm = this._formBuilder.group({
    });
    this.getCampos();
  }

  getCampos(){
    this.municipioService.getCamposTemplates(this._data.id_tramite).subscribe(resp=>{
      this.campos = resp;
      this.getRespuestas();
    });
  }

  getRespuestas(){
    this.municipioService.getRespuestasTemplates(this._data.id_tramite, this._data.id_tramite_construccion, this._data.id_municipio).subscribe(resp=>{
      this.respuestas = resp;
      for(var x=0; x<this.campos.length; x++){
        const result = this.respuestas.find(({ id_campo }) => id_campo === this.campos[x].id);
        if(result!=null){
          this.campos[x].value = result.respuesta;
          this.campos[x].id_tramite_construccion= this._data.id_tramite_construccion;
        }
      }
    });
  }

  saveCampos(){
    var error = false;
    for(var x=0; x<this.campos.length; x++){
      //aqui mandar a crear o a guardar this.campos[x]
      this.municipioService.storeRespuestaTemplate(this.campos[x]).subscribe((result) => {
      },(error) => {
        error=true;        
      });
    }
    if(error){
      Swal.fire({
        title: "Ocurrio un error al guardar!",
        icon: "warning",
        confirmButtonText: "Ok",
      });  
    }else{
      Swal.fire({
        title: "¡Éxito!",
        text: "Guardado correctamente!",
        icon: "success",
        confirmButtonText: "Ok",
      });
    }
  }

  setValue(i, value){
    this.campos[i].value=value;
    this.campos[i].id_tramite_construccion= this._data.id_tramite_construccion;
  }

  emitirResolutivo(){
    Swal.fire({
      title: '¿Estás seguro que deseas emitir el resolutivo?',
      text: 'Ya no podrás hacer modificaciones despues de emitirlo',
      showCancelButton: true,
      confirmButtonText: 'Sí, emitir',
      cancelButtonText: `Cancelar`,
      confirmButtonColor: "#003E76",
    }).then((result) => {
      if (result.isConfirmed) {
        this.saveCampos();
        this.municipioService.emitirResolutivo(this._data.id_tramite_construccion).subscribe(resp=>{
          Swal.fire({
            title: "¡Éxito!",
            text: "Emitido correctamente!",
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
    });
  }
}