import { HttpClient, HttpErrorResponse, HttpEventType } from '@angular/common/http';
import { ChangeDetectorRef, Component, OnInit, ViewChild } from '@angular/core';
import { FormArray, FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { MatDialog } from '@angular/material/dialog';
import { ActivatedRoute, Router } from '@angular/router';
import { TokenService } from '@core/authentication/token.service';
import { environment } from '@env/environment';
import { FuseSplashScreenService } from '@fuse/services/splash-screen.service';
import { CamposTramiteServiceService } from 'app/services/tramite/iniciar-tramite/campos-tramite-service.service';
import { ResumenService } from 'app/services/tramite/resumen.service';
import { map } from 'lodash';
import { of } from 'rxjs';
import { catchError } from 'rxjs/operators';
import Swal from 'sweetalert2';
import { DialogHistorialComponent } from '../ingresar-tramite/tramite/shared/dialog-historial/dialog-historial.component';
import { RevisionM } from '../tramites/revision/revision.models';
import { RevisionService } from '../tramites/revision/services/revision.service';
import { EmitirRefrendoComponent } from './emitir-refrendo/emitir-refrendo.component';

const ELEMENT_DATA: any[] = [
  {position: 1, name: 'Hydrogen', weight: 1.0079, symbol: 'H'},
  {position: 2, name: 'Helium', weight: 4.0026, symbol: 'He'},
  {position: 3, name: 'Lithium', weight: 6.941, symbol: 'Li'},
  {position: 4, name: 'Beryllium', weight: 9.0122, symbol: 'Be'},
  {position: 5, name: 'Boron', weight: 10.811, symbol: 'B'},
  {position: 6, name: 'Carbon', weight: 12.0107, symbol: 'C'},
  {position: 7, name: 'Nitrogen', weight: 14.0067, symbol: 'N'},
  {position: 8, name: 'Oxygen', weight: 15.9994, symbol: 'O'},
  {position: 9, name: 'Fluorine', weight: 18.9984, symbol: 'F'},
  {position: 10, name: 'Neon', weight: 20.1797, symbol: 'Ne'},
];
@Component({
  selector: 'app-refrendo',
  templateUrl: './refrendo.component.html',
  styleUrls: ['./refrendo.component.scss']
})
export class RefrendoComponent implements OnInit {
  displayedColumns: string[] = ['position', 'name', 'weight', 'symbol'];
  dataSource = ELEMENT_DATA;

  @ViewChild('img') imagen;
  @ViewChild('attachments') attachment: any;

  progress: number = 0;
  mostrarbar: string = null;
  env = environment.SERVER_ORIGIN;
  filterForm: FormGroup;
  filterForm_step2: FormGroup;
  filterForm_step3: FormGroup;
  filterForm_step4: FormGroup;
  step_actual = null;
  step1_visible = false;
  step2_visible = false;
  step3_visible = false;
  step4_visible = false;
  loading = true;
  step1_completed = false;
  step2_completed = false;
  step3_completed = false;
  step4_completed = false;
  isExpanded1 = true;
  isExpanded2 = false;
  isExpanded3 = false;
  id = 1;
  isExpanded4 = false;
  formCompleted = false;
  step1_propietarios = false;
  folio = '';
  filterFields: any[];
  camposDinamicos: [];
  camposFijos: [];
  camposData = [];
  calle = '';
  colonia = '';
  submitted = false;
  imageContainer = [];
  imageChangedEvent: any = {};
  croppedImage: any = {};
  campos: string[] = [];
  camposFile: string[] = [];
  camposLabel = [];
  camposLabel2 = [];
  camposLabel3 = [];
  camposLabel4 = [];
  mensaje = '';
  role = 0;
  numero_licencia= 0;
  resolucionRevisores = [];
  resolucion = [];
  dialogRef;  aprobado_director = 0;
  public modelRequsito: RevisionM = new RevisionM();
  options: FormGroup;
  hideRequiredControl = new FormControl(false);
  floatLabelControl = new FormControl('auto');

  filesForm: FormGroup;
  description: string;
  constructor(private HttpClient: HttpClient,
    private fb: FormBuilder,
    private fb4: FormBuilder,
    private _campoDinamico: CamposTramiteServiceService,
    private cd: ChangeDetectorRef,
    private route2: Router,
    private _resumenService: ResumenService,
    private activatedRoute: ActivatedRoute,
    private splash: FuseSplashScreenService,
    private _token: TokenService,
    private _matDialog: MatDialog,
    public dialog: MatDialog,
    private _revision: RevisionService,
    private fbDinamic: FormBuilder) {
    this.filesForm = this.fbDinamic.group({
        name: '',
        quantities: this.fb.array([]) ,
    });
   this.filterForm_step2 = this.fb.group({
      test: [null]
    });
    this.filterForm_step3 = this.fb.group({
      test: [null]
    });
    this.filterForm_step4 = this.fb4.group({
      test: [null]
    });
    this.filterForm = this.fb.group({
      test: [null]
    });
    this._campoDinamico.getCamposDinamicosRefrendo().subscribe(
      (res: any) => {
        this.camposFijos = res[1][0].data;
        this.camposDinamicos = JSON.parse(JSON.stringify(res[0].data.concat(this.camposFijos)));
        res[0].data.forEach(element => {
          if (element.step == 1) {
            this.step1_visible = true;
            //this.step1_completed = false;
          }
          if (element.step == 2) {
            this.step2_visible = true;
            //this.step2_completed = false;
          }
          if (element.step == 3) {
            this.step3_visible = true;
            //this.step3_completed = false;
          }
          if (element.step == 4) {
            this.step4_visible = true;
            // this.step4_completed = false;
          }
        });
        this.folio = this.activatedRoute.snapshot['_routerState']._root.children[0].children[0].value.params.folio;
        this.filterForm = this.generateFilterForm(1);
        this.filterForm_step2 = this.generateFilterForm(2);
        this.filterForm_step4 = this.generateFilterForm(4);
        this.filterForm_step3 = this.generateFilterForm(3);
        this.role = _token.get().role;
        console.log(this.role);
        this.getInfoConsultaRequisitos();
      },
      error => {
      }
    );
    this.validarIngreso();
  }

  public GetCurrentUserInformation(): Promise<any> {
    return this._campoDinamico.getCamposDinamicosRefrendo().toPromise()
  }
 
  fileChangeEvent(event, keyName, idx=null): void {
    !!idx // esto lo puse (niji) solo para validar la llamada de esta función que tiene una i al final de los argumentos y me marca error en el ide
    var numero_files = event.target.files.length;
    this.submitted = false;
    for (var i = 0; i < numero_files; i++) {
      if (event.target.files[i].size > 5242880) {
        Swal.fire({
          title: 'Error!',
          text: 'El Archivo no puede ser mayor a 15 MB!',
          icon: 'error',
          confirmButtonText: 'Ok'
        });

      } else {
        this.imageContainer[keyName] = event.target.files[i];
        this.uploadFile(event.target.files[i], keyName)
        this.campos.push(keyName);
      }
    }
  }
  verResumen() {
    var folio = this.activatedRoute.snapshot['_routerState']._root.children[0].children[0].value.params.folio;
    this.route2.navigate([`tramite/resumen/${folio}`])
  }
  uploadFile(file, argument) {
    var folio = this.activatedRoute.snapshot['_routerState']._root.children[0].children[0].value.params.folio;
    const formData = new FormData();
    formData.append("file", file);
    formData.append("folio", folio);

}
  imageCropped(image: any, keyName) {
    this.croppedImage[keyName] = image.base64;
  }
  ngOnInit() {
    this.getCamposDinamicos();
     this.getData();
  }
  buildFormData(formData, data, parentKey) {
    if (data && typeof data === 'object' && !(data instanceof Date) && !(data instanceof File)) {
      Object.keys(data).forEach(key => {
        this.buildFormData(formData, data[key], parentKey ? `${parentKey}[${key}]` : key);
      });
    } else {
      const value = data == null ? '' : data;
      formData.append(parentKey, value);
    }
  }
  jsonToFormData(data) {

    const formData = new FormData();
    this.buildFormData(formData, data, null);
    return formData;
  }
  getFormData2 = object => Object.keys(object).reduce((formData, key) => {
    if (object[key] != null) {
      formData.append(key, object[key]);
    }
    return formData;
  }, new FormData());

  openDialog(row){
    this.dialog.open(DialogHistorialComponent, {
      data: {
        detalle: row,
      },
    });
  }
  getFormData(object) {
    const formData = new FormData();
    Object.keys(object).forEach(key =>
      formData.append(key, object[key])
    );
    var folio = this.activatedRoute.snapshot['_routerState']._root.children[0].children[0].value.params.folio;
    formData.append("folio", folio);
    formData.append("step_actual", this.step_actual);

    return formData;
  }
  getErrosForm(form, camposLabel) {
    this.mensaje = '';

    if (typeof form['anexo_1'] !== 'undefined') {
      form['anexo_1'].status = "VALID";
    }
    if (typeof form['anexo_2'] !== 'undefined') {
      form['anexo_2'].status = "VALID";
    }
    if (typeof form['anexo_3'] !== 'undefined') {
      form['anexo_3'].status = "VALID";
    }
    if (typeof form['anexo_4'] !== 'undefined') {
      form['anexo_4'].status = "VALID";
    }

    for (let property1 in form) {
      if (form[property1].status != 'DISABLED') {
       
        if (!form[property1].valid) {
          if (form[property1].errors.required != undefined) {
            this.mensaje = this.mensaje + '<li style ="padding-left: 0em;text-indent: -1.7em;">El Campo ' + '' + camposLabel[property1] + ' es requerido</li>';
          } else {
            if (form[property1].errors.minlength != undefined || form[property1].errors.maxlength != undefined) {
              this.mensaje = this.mensaje + '<li>El Campo ' + '' + camposLabel[property1] + ' no cumple con la longitud legal</li>';
            }
            if (form[property1].errors.email != undefined) {
              this.mensaje = this.mensaje + '<li>El Campo ' + '' + camposLabel[property1] + ' no es valido</li>';
            }
            if (form[property1].errors.pattern != undefined) {
              this.mensaje = this.mensaje + '<li>El Campo ' + '' + camposLabel[property1] + ' contiene caracteres invalidos</li>';
            }

          }
        }
      }
    }
  }
  validador(formulario) {
    const propOwn = Object.getOwnPropertyNames(formulario.value);
    let valido = formulario.valid;
    let contadorInvalidos = 0;
    if (!valido) {
      for (var prop in formulario.value) {
        if (formulario.value[prop] == '' && formulario.value[prop] == 'VALID') {
        } else {
          if (formulario.controls[prop].status != 'VALID') {
            contadorInvalidos++;
          }
        }
      }
    }
    if (contadorInvalidos == 0) {
      valido = true;
    } else {
      valido = false;
    }

    return valido;
  }
  Submit(step, event): void {
    this.splash.show();
    event.preventDefault();
    var filter_form = null;
    var valido = false
    if (step == 1) {
      filter_form = this.filterForm.value;
      valido = this.filterForm.valid;
      this.getErrosForm(this.filterForm.controls, this.camposLabel);
      valido = this.validador(this.filterForm);
      this.step_actual = 1;
    }
    if (step == 2) {
      filter_form = this.filterForm_step2.value;
      this.getErrosForm(this.filterForm_step2.controls, this.camposLabel2);
      valido = this.validador(this.filterForm_step2);
      this.step_actual = 2;
    }
    if (step == 3) {
      filter_form = this.filterForm_step3.value;
      let contadorInvalidos = 0;
      this.getErrosForm(this.filterForm_step3.controls, this.camposLabel3);
      valido = this.validador(this.filterForm_step3);
      this.step_actual = 3;
    }
    if (step == 4) {
      filter_form = this.filterForm_step4.value;
      this.getErrosForm(this.filterForm_step4.controls, this.camposLabel4);
      valido = this.validador(this.filterForm_step4);
      this.step_actual = 4;
      if (this._token.get().role == 4) {
        valido = true;
      }
    }
    this.submitted = true;
    if (valido) {

      this.validateAll(filter_form).then((arrayValidated) => {
        var json_arr = JSON.stringify(arrayValidated);
        const formData = new FormData();
        this._campoDinamico.uploadDataRefrendo(this.getFormData(arrayValidated)).pipe()
          .subscribe(
            result => {
              this.splash.hide();
              // this.isExpanded2     = true;
              this.extandex(this.step_actual);
              Swal.fire({
                title: 'Sección completada',
                text: 'La información se guardó correctamente',
                icon: 'success',
                confirmButtonText: 'Ok'
              }).then(
                () => {
                  this.validarIngreso();
                }
              );
            },
            error => {
              Swal.fire({
                title: 'Error!',
                text: error.error.error,
                icon: 'error',
                confirmButtonText: 'Ok'
              });
            }
          );
      });
    } else {
      this.splash.hide();
      Swal.fire({
        title: 'Ups!',
        html: '<ul style="font-size: 11px;color: indianred;font-family: monospace;margin: 0;">' + this.mensaje + '</ul>',
        icon: 'warning',
        confirmButtonText: 'Ok'
      });

    }
  }
  validateAll(filter_form) {
    return new Promise((resolve, reject) => {
      let allValues = filter_form
      Object.keys(filter_form).forEach((key, index) => {
        if (this.imageContainer[key]) {
          allValues[key] = null;
          delete allValues[key];
        }
        if (Object.keys(allValues).length > 0) {
          resolve(allValues)
        } else {
          resolve(true)
        }
      });
    })
  }
  generateFilterForm(step): FormGroup {
    var baseForm;
    if (step == 4) {
      baseForm = this.fb4.group({});
    } else {
      baseForm = this.fb.group({});
    }

    this.camposDinamicos.forEach((field: any) => {
      var disabled = false;
      if (step == 2) {
        this.camposLabel2[field.name] = field.description;
      }
      if (step == 3) {
        this.camposLabel3[field.name] = field.description;
      }
      if (step == 4) {
        this.camposLabel4[field.name] = field.description;
      }
      if (step == 1) {
        this.camposLabel[field.name] = field.description;
      }
      if (step == field.step) {      
        if (field.tipo_tramite == "consulta" || field.tipo_tramite == "consulta_requisitos") {
          disabled = true;
        } else {
          disabled = false;
        }
        if (field.type == "file" || field.type == "multifile") {
          if (field.value == null) {
            if (field.name != 'anexo_1' || field.name != 'anexo_2' || field.name != 'anexo_3' || field.name != 'anexo_4') {
              if (this._token.get().role > 1) {
                if(field.requerido_funcionario == 1){
                  baseForm.addControl(field.name, new FormControl('', [Validators.required]));
                }else{
                  baseForm.addControl(field.name, new FormControl(''));
                }  
              } else {
                baseForm.addControl(field.name, new FormControl('', [Validators.required]));
              }
            } else {
              baseForm.addControl(field.name, new FormControl('', null));
            }
          } else {
            baseForm.addControl(field.name, new FormControl('', null));
          }
        } else {
         
          var required = null;
          if (this.role == 4) {
            required = null;
          } else {
            required = Validators.required;
          }
          if (field.type == "input" && field.description_rec == '|+|') {
            var maximo_char = 999999999;
            var minimo_char = 0;
            if (field.name.indexOf('cp_') > -1) {
              maximo_char = 5;
              minimo_char = 5;
            }
            if (field.name.indexOf('curp') > -1) {
              maximo_char = 18;
              minimo_char = 18;
            }
            if (field.name.indexOf('rfc') > -1) {
              maximo_char = 13;
              minimo_char = 12;
            }
            if (field.name.indexOf('correo') > -1) {
              if (this._token.get().role > 1) {
                if(field.requerido_funcionario == 1){
                  baseForm.addControl(field.name, new FormControl({ value: field.value, disabled: disabled }, [, Validators.required, Validators.email]));
                }else{
                  baseForm.addControl(field.name, new FormControl({ value: field.value, disabled: disabled }));
                }
        
              } else {
                baseForm.addControl(field.name, new FormControl({ value: field.value, disabled: disabled }, [, Validators.required, Validators.email]));
              }
            } else {
              if (this._token.get().role > 1) {
                if(field.requerido_funcionario == 1){
                  baseForm.addControl(field.name, new FormControl({ value: field.value, disabled: disabled },[required, Validators.pattern('^[\.a-zA-Z0-9,!? ñÑáéíóúÁÉÍÓÚ@]*$'), Validators.maxLength(maximo_char), Validators.minLength(minimo_char)]));
                }else{
                  baseForm.addControl(field.name, new FormControl({ value: field.value, disabled: disabled },[Validators.pattern('^[\.a-zA-Z0-9,!? ñÑáéíóúÁÉÍÓÚ@]*$')]));
                }   
              } else {
                baseForm.addControl(field.name, new FormControl({ value: field.value, disabled: disabled },[required, Validators.pattern('^[\.a-zA-Z0-9,!? ñÑáéíóúÁÉÍÓÚ@]*$'), Validators.maxLength(maximo_char), Validators.minLength(minimo_char)]));
              }
            }
          }else{
            console.log(field.type,field.name);
            baseForm.addControl(field.name, new FormControl({ value: field.value, disabled: disabled }, Validators.pattern('^[\.a-zA-Z0-9,!? ñÑáéíóúÁÉÍÓÚ@]*$')));
          }
        }
      }
    });
    this.loading = false;
    return baseForm;
  }
  emitirDialog(row) {
    console.log(atob(this.folio));
    this.dialogRef = this._matDialog.open(EmitirRefrendoComponent, {
        panelClass: 'emitir-form-dialog',
        width:'50%',
        disableClose:true,
        data: {
            action: 'new',
            data: atob(this.folio),
            num_licencia :this.numero_licencia
        }
    });
    this.dialogRef.afterClosed()
        .subscribe((response: FormGroup) => {
         
    });
  }
  getInfoConsultaRequisitos() {
   
    this._resumenService.getInfoTramiteRe(atob(this.folio)).subscribe(
      (r: any) => {
        this.numero_licencia = r.data['lic_v'];
        this.calle   = r.data['info'].calle;
        this.colonia = r.data['info'].colonia;
        if (r.data['info'].tipo_persona == 'Física' && r.data['info'].caracter_solicitante == 'Propietario') {
          this.step1_propietarios = true;
        } else {
          this.step1_propietarios = false;
        }
      }, e => {
        console.error(e);
      }
    );
  }
  getCamposDinamicos() {
    this._campoDinamico.getCamposDinamicosRefrendo().subscribe(
      (res: any) => {
        this.camposFijos = res[1][0].data;
        this.camposDinamicos = res[0].data.concat(this.camposFijos);
      },
      error => {
      }
    );
  }
  extandex(step) {
    step = step + 1;
    if (step == 5) {
      step = 4;
    }
    var max1 = (step == 1) ? true : false;
    var max2 = (step == 2) ? true : false;
    var max3 = (step == 3) ? true : false;
    var max4 = (step == 4) ? true : false;

    this.isExpanded1 = max1;
    this.isExpanded2 = max2;
    this.isExpanded3 = max3;
    this.isExpanded4 = max4;
  }
  validarIngreso() {
    const formData = new FormData();
    var folio = this.activatedRoute.snapshot['_routerState']._root.children[0].children[0].value.params.folio;
    formData.append("folio", folio);
    this._campoDinamico.validarIngresoRefrendo(formData).subscribe(
      (res: any) => {
        this.step1_completed = (res['step_uno'] == 1) ? true : false;
        this.step2_completed = (res['step_dos'] == 1) ? true : false;
        this.step3_completed = (res['step_tres'] == 1) ? true : false;
        this.step4_completed = (res['step_cuatro'] == 1) ? true : false;
        if (res['step_uno'] == 1 && res['step_dos'] == 1 && res['step_tres'] == 1 && res['step_cuatro'] == 1) {
          this.formCompleted = true;
        }
        this.extandex(res['step_actual']);
      },
      error => {
      }
    );
  }
  // Generar licencia 
  getData() {
    return new Promise((resolve) => {
 
      var folio = this.activatedRoute.snapshot['_routerState']._root.children[0].children[0].value.params.folio;

      this._revision
        .getDataRevisionRefrendo (folio)
        .toPromise()
        .then((r: any) => {
          this.resolucionRevisores = r.data.resolucion_revisores;
          this.resolucion = r.data.resolucion;
        
          if(this.role==4){
            this.aprobado_director = r.data.tramite.aprobado_director ?? 0;
          }
          this.modelRequsito.resolucion =
            r.data.resolucion.length > 0 ? r.data.resolucion[0].resolucion_text : '';
          this.modelRequsito.status_resolucion =
            r.data.resolucion.length ? r.data.resolucion[0].resolucion_status.toString() : "";
          resolve("Resolved");
        }).catch(e => {
          resolve("false")
           console.log(e);
        });
    });
  }

 
fileList: File[] = [];
descriptiones: any[] = [];
listOfFiles: any[] = [];
desValida :boolean = true;

 onFileChanged(event: any) {
  if(this.description != ''){
    this.desValida = true;
      for (var i = 0; i <= event.target.files.length - 1; i++) {
          var selectedFile = event.target.files[i];
          this.fileList.push(selectedFile);
          this.descriptiones.push(this.description);
          this.listOfFiles.push({'name':selectedFile.name,'description':this.description})
      }
  }else{
    this.desValida = false;
  }
 
  this.description = '';
  this.attachment.nativeElement.value = '';
}
removeSelectedFile(index) {
 // Delete the item from fileNames list
 this.listOfFiles.splice(index, 1);
 // delete file from FileList
 this.fileList.splice(index, 1);
}
openDialogMap(){

}
uploadFiles(){
  const formData = new FormData();
  console.log(this.fileList);
  for (var i = 0; i < this.fileList.length; i++) { 
    formData.append("file[]", this.fileList[i]);
    formData.append("description[]", this.descriptiones[i]);
  }
  console.log(atob(this.folio));
  this._revision.uploadFile(formData, 1).subscribe(r => { 
    Swal.fire({
      title: 'Archivos guardados Correctamente',
      icon: 'success',
      confirmButtonText: 'Continuar'
    }).then(e=>{
    
    })
  },e=>{
    Swal.fire({
      title: '¡Algo a ocurrido!',
      text:'Intentar más tarde o contactar al soporte',
      icon: 'error',
      confirmButtonText: 'Continuar'
    })
  });
  console.log(formData);

}
}