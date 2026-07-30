import { HttpClient, HttpErrorResponse, HttpEventType } from '@angular/common/http';
import { ChangeDetectorRef, Component, OnInit, ViewChild } from '@angular/core';
import { FormBuilder, FormGroup, FormControl, Validators } from '@angular/forms';
import { CamposTramiteServiceService } from 'app/services/tramite/iniciar-tramite/campos-tramite-service.service';
import { of } from 'rxjs';
import Swal from 'sweetalert2';
import { catchError, map } from 'rxjs/operators';
import { ActivatedRoute, Router } from '@angular/router';
import { ResumenService } from 'app/services/tramite/resumen.service';
import { environment } from '@env/environment';
import { FuseSplashScreenService } from '@fuse/services/splash-screen.service';
import { TokenService } from '@core/authentication/token.service';
import { noRequeridos } from './norequeridos';
@Component({
  selector: 'app-tramite',
  templateUrl: './tramite.component.html',
  styleUrls: ['./tramite.component.scss', '../formulario-vu.scss']
})

export class TramiteComponent implements OnInit {

  @ViewChild('img') imagen;
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
  constructor(private HttpClient: HttpClient,
    private fb: FormBuilder,
    private fb4: FormBuilder,
    private _campoDinamico: CamposTramiteServiceService,
    private cd: ChangeDetectorRef,
    private route2: Router,
    private _resumenService: ResumenService,
    private activatedRoute: ActivatedRoute,
    private splash: FuseSplashScreenService,
    private _token: TokenService) {

      this.role = _token.get().role;
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
    this._campoDinamico.getCamposDinamicos().subscribe(
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
        this.folio = this.activatedRoute.snapshot.params.folio;
        this.filterForm = this.generateFilterForm(1);
        this.filterForm_step2 = this.generateFilterForm(2);
        this.filterForm_step4 = this.generateFilterForm(4);
        this.filterForm_step3 = this.generateFilterForm(3);
        
        console.log(this.role);
        this.getInfoConsultaRequisitos();
      },
      error => {
      }
    );
    this.validarIngreso();
  }

  public GetCurrentUserInformation(): Promise<any> {
    return this._campoDinamico.getCamposDinamicos().toPromise()
  }
  /*
      fileChangeEvent(event: any, keyName, index:any): void {
        this.imageChangedEvent[keyName] = event;
        
       if(event.target.files.length > 0) 
        {
          console.log(event.target.files[0].name);
          this.camposDinamicos[index].imgUrl = "assets/images/icons/ico_cel.svg";
            
        }else{
          this.camposDinamicos[index].imgUrl = "assets/images/icons/ico_upload.svg";       
        }
      }
  */
  fileChangeEvent(event, keyName): void {
    var numero_files = event.target.files.length;
    this.submitted = false;
    for (var i = 0; i < numero_files; i++) {
      if (event.target.files[i].size > 15242880) {
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
    // var folio = this.activatedRoute.snapshot['_routerState']._root.children[0].children[0].value.params.folio;
    this.route2.navigate([`tramite/resumen/${this.folio}`])
  }
  uploadFile(file, argument) {
    // var folio = this.activatedRoute.snapshot['_routerState']._root.children[0].children[0].value.params.folio;
    const formData = new FormData();
    formData.append("file", file);
    formData.append("folio", this.folio);

    this._campoDinamico.upload(formData, argument).pipe(
      map(event => {

        switch (event.type) {
          case HttpEventType.Sent:
            break;
          case HttpEventType.ResponseHeader:
            break;
          case HttpEventType.UploadProgress:
            this.progress = Math.round(event.loaded * 1000 / event.total);
            this.mostrarbar = argument;
            break;
          case HttpEventType.Response:

            setTimeout(() => {
              this.progress = 0;
              this.mostrarbar = null;
            }, 1500);
        }
      }),
      catchError((error: HttpErrorResponse) => {
        var Error = error.error.error;
        Swal.fire({
          title: 'Error!',
          text: Error,
          icon: 'error',
          confirmButtonText: 'Ok'
        });

        file.inProgress = false;
        return of(`${argument} upload failed.`);
      })).subscribe((event: any) => {
        if (typeof (event) === 'object') {

        }
      });
  }

  imageCropped(image: any, keyName) {
    this.croppedImage[keyName] = image.base64;
  }
  ngOnInit() {
    this.getCamposDinamicos();
    console.log(this.detectBrowserName());
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

  getFormData(object) {
    const formData = new FormData();
    Object.keys(object).forEach(key =>
      formData.append(key, object[key])
    );
    // var folio = this.activatedRoute.snapshot['_routerState']._root.children[0].children[0].value.params.folio;
    formData.append("folio", this.folio);
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
    }
    this.submitted = true;
    if (valido) {

      this.validateAll(filter_form).then((arrayValidated) => {
        var json_arr = JSON.stringify(arrayValidated);
        const formData = new FormData();
        this._campoDinamico.uploadData(this.getFormData(arrayValidated)).pipe()
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
             
              if (this.role > 1) {
                  baseForm.addControl(field.name, new FormControl(''));   
              } else {
           
                baseForm.addControl(field.name, new FormControl(''));   
             //   baseForm.addControl(field.name, new FormControl('', [Validators.required]));
              }
            } else {
              
              baseForm.addControl(field.name, new FormControl('', null));
            }
          } else {
            if (this.role > 1) {
                baseForm.addControl(field.name, new FormControl(''));
            } else{
              baseForm.addControl(field.name, new FormControl('', null));
            }
            
          }
        } else {
         //console.log(field.name,field.type,noRequeridos.indexOf(field.name));
          let required = null;
          // console.log(noRequeridos.indexOf(field.name));
          // console.log(this.role,'role');
          // console.log(field.name);
          if (this.role > 1 && !noRequeridos.indexOf(field.name)) {

            console.log(123);
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
            if (field.name.indexOf('rfc') > -1 && this.role > 1) {
              maximo_char = 18;
              minimo_char = 0;
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
                if(noRequeridos.indexOf(field.name)>-1){
           //       console.log(field.name, 'Existe en array',noRequeridos.indexOf(field.name));
                  baseForm.addControl(field.name, new FormControl({ value: field.value, disabled: disabled },[Validators.pattern('^[\.a-zA-Z0-9,!? ñÑáéíóúÁÉÍÓÚ@]*$'), Validators.maxLength(maximo_char), Validators.minLength(minimo_char)]));
                }else{
                  baseForm.addControl(field.name, new FormControl({ value: field.value, disabled: disabled },[Validators.required,Validators.pattern('^[\.a-zA-Z0-9,!? ñÑáéíóúÁÉÍÓÚ@]*$')]));
                }   
              } else {
                baseForm.addControl(field.name, new FormControl({ value: field.value, disabled: disabled },[Validators.required, Validators.pattern('^[\.a-zA-Z0-9,!? ñÑáéíóúÁÉÍÓÚ@]*$'), Validators.maxLength(maximo_char), Validators.minLength(minimo_char)]));
              }
            }
          }else{
            if (this._token.get().role > 1) {
              if(noRequeridos.indexOf(field.name)>-1){
             //   console.log(field.name, 'Existe en array',noRequeridos.indexOf(field.name));
                baseForm.addControl(field.name, new FormControl({ value: field.value, disabled: disabled },[Validators.pattern('^[\.a-zA-Z0-9,!? ñÑáéíóúÁÉÍÓÚ@]*$'), Validators.maxLength(maximo_char), Validators.minLength(minimo_char)]));
              }else{
                baseForm.addControl(field.name, new FormControl({ value: field.value, disabled: disabled },[Validators.required,Validators.pattern('^[\.a-zA-Z0-9,!? ñÑáéíóúÁÉÍÓÚ@]*$')]));
              }   
            }else{  
              baseForm.addControl(field.name, new FormControl({ value: field.value, disabled: disabled },[Validators.pattern('^[\.a-zA-Z0-9,!? ñÑáéíóúÁÉÍÓÚ@]*$'), Validators.maxLength(maximo_char), Validators.minLength(minimo_char)]));
            }
          }
        }
      }
    });
    this.loading = false;

    //console.log(baseForm);
    return baseForm;
  }

  getInfoConsultaRequisitos() {
    this._resumenService.getInfoTramite(atob(this.folio)).subscribe(
      (r: any) => {
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
  /*generateFormGroup(baseForm: FormGroup, field): FormGroup {
    var disabled = false;
      if (field.group) {
        const formGroup = this.fb.group({});
        field.group.forEach(item => {
          formGroup.addControl(item.name,this.generateFormGroup(formGroup, item));
        });
        return formGroup;
      } else {
      }
      setTimeout(function(){    return baseForm; }, 3000);
  }*/

  getCamposDinamicos() {
    this._campoDinamico.getCamposDinamicos().subscribe(
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
    this._campoDinamico.validarIngreso(formData).subscribe(
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

  detectBrowserName() { 
    const agent = window.navigator.userAgent.toLowerCase();
    switch (true) {
      case agent.indexOf('edge') > -1:
        return 'edge';
      case agent.indexOf('opr') > -1 && !!(<any>window).opr:
        return 'opera';
      case agent.indexOf('chrome') > -1 && !!(<any>window).chrome:
        return 'chrome';
      case agent.indexOf('trident') > -1:
        return 'ie';
      case agent.indexOf('firefox') > -1:
        return 'firefox';
      case agent.indexOf('safari') > -1:
        return 'safari';
      default:
        return 'other';
    }
  }
}