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
import { MatDialog } from '@angular/material/dialog';
import { EmitirProrrogaComponent } from '../emitir-prorroga/emitir-prorroga.component';
import { FileUploadService } from 'app/services/administrador/municipios/file-upload.service';
import {DateAdapter} from '@angular/material/core';
import { MAT_DATE_FORMATS } from '@angular/material/core';

export const MY_FORMATS = {
  parse: {
    dateInput: 'YYYY-MM-DD',
  },
  display: {
    dateInput: 'DD-MM-YYYY',
    monthYearLabel: 'DD-MM-YYYY',
    dateA11yLabel: 'DD-MM-YYYY',
    monthYearA11yLabel: 'DD-MM-YYYY',
  },
};

@Component({
  selector: 'app-tramite-edit',
  templateUrl: './tramite-edit.component.html',
  styleUrls: ['./tramite-edit.component.scss'],
  providers: [{ provide: MAT_DATE_FORMATS, useValue: MY_FORMATS }]
})
export class TramiteEditComponent implements OnInit {

  @ViewChild('img') imagen;
  arrayNameFiles = ['input0'];
  arrayValuesFiles = ['file0'];
  arrayValuesFiles_2 = [];
  count = 0;
  progress: number = 0;
  mostrarbar: string = null;
  env = environment.SERVER_ORIGIN;
  filterForm: FormGroup;
  filterForm_step2: FormGroup;
  filterForm_step3: FormGroup;
  filterForm_step4: FormGroup;
  filterForm_step5: FormGroup;
  filterForm_static: FormGroup;
  filterForm_step6: FormGroup;
  step_actual = null;
  step1_visible = false;
  step2_visible = false;
  step3_visible = false;
  step4_visible = false;
  step5_visible = false;
  step6_visible = false;
  loading = true;
  step1_completed = false;
  step2_completed = false;
  step3_completed = false;
  step4_completed = false;
  step5_completed = false;
  isExpanded1 = true;
  isExpanded2 = false;
  isExpanded3 = false;
  isExpanded4 = false;
  isExpanded5 = false;
  formCompleted = false;
  step1_propietarios = false;
  folio = '';
  filterFields: any[];
  camposDinamicos: [];
  camposDinamicos2: [];
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
  camposLabel5 = [];
  mensaje = '';
  role = 0;
  int_mostrar_razon_social = false;
  prop_mostrar_razon_social = false;
  notarios_publicos: [];
  prorroga = 0;
  dialogRef;
  mts_total_value = 0;
  btnCssCancel = {
    "background-color": "red",
    color: "white",
  };
  formDataFiles: any = new FormData();
  constructor(private HttpClient: HttpClient,
    private fb: FormBuilder,
    private fb4: FormBuilder,
    private _campoDinamico: CamposTramiteServiceService,
    private cd: ChangeDetectorRef,
    private _matDialog: MatDialog,
    private route2: Router,
    private _resumenService: ResumenService,
    private activatedRoute: ActivatedRoute,
    private splash: FuseSplashScreenService,
    private uploadFileService: FileUploadService,
    private _token: TokenService,
    private dateAdapter: DateAdapter<any>) {
    this.dateAdapter.setLocale('es-MX');
    if (typeof this.activatedRoute.snapshot['_routerState']._root.children[0].children[0].value.params.tipo !== 'undefined') {
      this.prorroga = 1;
    }
    this.filterForm_step2 = this.fb.group({
      test: [null]
    });
    this.filterForm_step3 = this.fb.group({
      test: [null]
    });
    this.filterForm_step4 = this.fb4.group({
      test: [null],

    });
    this.filterForm_step5 = this.fb4.group({
      test: [null],

    });
    this.filterForm_step6 = this.fb4.group({});
    this.filterForm = this.fb.group({
      test: [null]
    });

    this.filterForm_static = this.fb.group({
      m2_habitacional: [''],
      m2_comercial: [''],
      m2_industrial: [''],
      m2_turistico: [''],
      m2_equipamiento: [''],
      m2_espacios_verdes: [''],
      m2_otro: [''],
      m2_a_construir: [''],
      m2_a_demoler: ['', Validators.required],
    });

    this.filterForm_static.valueChanges.subscribe((value) => {
      this.mts_total_value = parseFloat(this.filterForm_static.controls['m2_habitacional'].value) + parseFloat(this.filterForm_static.controls['m2_comercial'].value) +
      parseFloat(this.filterForm_static.controls['m2_industrial'].value) + parseFloat(this.filterForm_static.controls['m2_turistico'].value) +
      parseFloat(this.filterForm_static.controls['m2_equipamiento'].value) + parseFloat(this.filterForm_static.controls['m2_espacios_verdes'].value) +
      parseFloat(this.filterForm_static.controls['m2_otro'].value);
    });
    this._campoDinamico.getCamposDinamicosConstruccion().subscribe(
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
          if (element.step == 5) {
            this.step5_visible = true;
            // this.step4_completed = false;
          }
          if (element.step == 6) {
            this.step6_visible = true;
            // this.step4_completed = false;
          }
        });
        this.folio = this.activatedRoute.snapshot['_routerState']._root.children[0].children[0].value.params.folio;
        this.filterForm = this.generateFilterForm(1);
        this.filterForm_step2 = this.generateFilterForm(2);
        this.filterForm_step4 = this.generateFilterForm(4);
        this.filterForm_step3 = this.generateFilterForm(3)
        this.filterForm_step5 = this.generateFilterForm(5);
        this.role = _token.get().role;
        this.getInfoConsultaRequisitos();
      },
      error => {
      }
    );
    this.getRequisitosConstruccion();
    this.getNotarios();
    this.validarIngreso();
  }

  public GetCurrentUserInformation(): Promise<any> {
    return this._campoDinamico.getCamposDinamicosConstruccion().toPromise()
  }

  fileChangeEvent(event, keyName): void {
    var numero_files = event.target.files.length;
    this.submitted = false;
    for (var i = 0; i < numero_files; i++) {
      if (event.target.files[i].size > 5242880) {
        Swal.fire({
          title: 'Error!',
          text: 'El Archivo no puede ser mayor a 5 MB!',
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
    var id = this.activatedRoute.snapshot['_routerState']._root.children[0].children[0].value.params.id;
    console.log(id);
    if (typeof id == "string" && id == "2") {
      this.route2.navigate([`tramites`])
      return
    }
    this.route2.navigate([`tramite/resumen/${folio}`])
  }
  uploadFile(file, argument) {
    var folio = this.activatedRoute.snapshot['_routerState']._root.children[0].children[0].value.params.folio;
    const formData = new FormData();
    formData.append("file", file);
    formData.append("folio", folio);

    this._campoDinamico.uploadConstruccion(formData, argument).pipe(
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
    this.filterForm_step6.addControl('input'+this.count.toString(), new FormControl('', [Validators.required]));
    this.getCamposDinamicos();
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
    //this.splash.show();
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
      filter_form.fecha_dcto_propiedad = filter_form.fecha_dcto_propiedad.toISOString();
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
    if (step == 5) {
      filter_form = this.filterForm_step5.value;
      this.getErrosForm(this.filterForm_step5.controls, this.camposLabel5);
      valido = this.validador(this.filterForm_step5);
      this.step_actual = 5;
      if (this._token.get().role == 5) {
        valido = true;
      }
      var arrayData = [parseFloat(this.filterForm_static.controls['m2_habitacional'].value),
      parseFloat(this.filterForm_static.controls['m2_comercial'].value),
      parseFloat(this.filterForm_static.controls['m2_industrial'].value),
      parseFloat(this.filterForm_static.controls['m2_turistico'].value),
      parseFloat(this.filterForm_static.controls['m2_equipamiento'].value),
      parseFloat(this.filterForm_static.controls['m2_espacios_verdes'].value),
      parseFloat(this.filterForm_static.controls['m2_otro'].value),
      parseFloat(this.filterForm_static.controls['m2_a_demoler'].value)];

      this._campoDinamico.updateRequisitosConstruccion(arrayData, this.folio).subscribe(
        (res: any) => {
        },
        error => {
          console.log(error)
        });
    }
    if (step == 6) {
      filter_form = this.filterForm_step6.value;
      this.step_actual = 6;
      valido = true;
    }
    this.submitted = true;
    if (valido) {
      this.validateAll(filter_form).then((arrayValidated) => {
        var json_arr = JSON.stringify(arrayValidated);
        const formData = new FormData();
        if(this.step_actual!=6){
          this._campoDinamico.uploadDataConstruccion(this.getFormData(arrayValidated)).pipe()
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

        }else{
          console.log(arrayValidated);
          this._campoDinamico.uploadFilesProrrogaConstruccion(arrayValidated, this.arrayValuesFiles_2, this.folio).pipe()
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
        }
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
      let allValues = filter_form;
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
      if (step == 5) {
        this.camposLabel5[field.name] = field.description;
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

                if (field.requerido_funcionario == 1) {
                  baseForm.addControl(field.name, new FormControl('', [Validators.required]));
                } else {
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
              baseForm.addControl(field.name, new FormControl({ value: field.value, disabled: disabled }, [, Validators.required, Validators.email]));
            } else {
              if (this._token.get().role > 1) {
                if(field.name == 'dro_no_registro' || field.name == 'lic_info_adicional'){
                  baseForm.addControl(field.name, new FormControl({ value: field.value, disabled: disabled }, [Validators.required]));
                }else if(field.name == 'lic_vigencia'){
                  baseForm.addControl(field.name, new FormControl({ value: field.value, disabled: disabled }, [Validators.required, Validators.pattern('^[0-9]*$')]));
                }else if(field.name == 'fecha_dcto_propiedad'){
                  let newDate = new Date(field.value);
                  baseForm.addControl(field.name, new FormControl({ value: newDate, disabled: disabled }, [Validators.required]));
                }else{
                  baseForm.addControl(field.name, new FormControl({ value: field.value, disabled: disabled }, [Validators.pattern('^[\.a-zA-Z0-9,!? ñÑáéíóúÁÉÍÓÚ@]*$')]));
                }
              } else {
                baseForm.addControl(field.name, new FormControl({ value: field.value, disabled: disabled }, [required, Validators.pattern('^[\.a-zA-Z0-9,!? ñÑáéíóúÁÉÍÓÚ@]*$'), Validators.maxLength(maximo_char), Validators.minLength(minimo_char)]));
              }
            }
          } else {
            if (field.name == 'int_tipo_persona' && field.value == 'Moral') {
              this.int_mostrar_razon_social = true;
              this.prop_mostrar_razon_social = true;
            }
            console.log(field.type, field.name);
            baseForm.addControl(field.name, new FormControl({ value: field.value, disabled: disabled }, Validators.pattern('^[\.a-zA-Z0-9,!? ñÑáéíóúÁÉÍÓÚ@]*$')));
          }
        }
      }
    });
    this.loading = false;
    return baseForm;
  }

  getInfoConsultaRequisitos() {
    this._resumenService.getInfoTramiteConstruccion(atob(this.folio)).subscribe(
      (r: any) => {
        this.calle = r.data['info'].calle;
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
    this._campoDinamico.getCamposDinamicosIdConstruccion().subscribe(
      (res: any) => {
        this.camposFijos = res[1][0].data;
        this.camposDinamicos = res[0].data.concat(this.camposFijos);
        this.camposDinamicos2 = res[2][0].data[0];

        console.log(this.camposDinamicos2);
      },
      error => {
      }
    );
  }
  extandex(step) {
    step = step + 1;
    if (step == 5) {
      step = 6;
    }
    var max1 = (step == 1) ? true : false;
    var max2 = (step == 2) ? true : false;
    var max3 = (step == 3) ? true : false;
    var max4 = (step == 4) ? true : false;
    var max5 = (step == 5) ? true : false;
    this.isExpanded1 = max1;
    this.isExpanded2 = max2;
    this.isExpanded3 = max3;
    this.isExpanded4 = max4;
    this.isExpanded5 = max5;
  }
  validarIngreso() {
    const formData = new FormData();
    var folio = this.activatedRoute.snapshot['_routerState']._root.children[0].children[0].value.params.folio;
    formData.append("folio", folio);
    this._campoDinamico.validarIngresoConstruccion(formData).subscribe(
      (res: any) => {
        this.step1_completed = (res['step_uno'] == 1) ? true : false;
        this.step2_completed = (res['step_dos'] == 1) ? true : false;
        this.step3_completed = (res['step_tres'] == 1) ? true : false;
        this.step4_completed = (res['step_cuatro'] == 1) ? true : false;
        this.step5_completed = (res['step_cinco'] == 1) ? true : false;
        if (res['step_uno'] == 1 && res['step_dos'] == 1 && res['step_tres'] == 1 && res['step_cuatro'] == 1 && res['step_cinco'] == 1) {
          this.formCompleted = true;
        }
        this.extandex(res['step_actual']);
      },
      error => {
      }
    );
  }

  validarPersona(name, value) {
    switch (name) {
      case 'int_tipo_persona':
        var valuesPersonFisica = ['int_nombre', 'int_apellidouno', 'int_apellidodos', 'int_curp', 'int_correo', 'int_cel'];
        var valuesPersonMoral = ['int_moral_rsocial', 'int_moral_rfc', 'int_moral_cp', 'int_moral_calle', 'int_moral_no_ext', 'int_moral_no_int', 'int_moral_colonia', 'int_moral_localidad', 'int_moral_mpio'];
        if (value == 'Fisica') {
          this.filterForm.controls[name].setValue(value);
          this.int_mostrar_razon_social = false;
          for(let i = 0; i < valuesPersonFisica.length; i++){ 
            this.filterForm.controls[valuesPersonFisica[i]].setValidators([Validators.required]);
            this.filterForm.controls[valuesPersonFisica[i]].updateValueAndValidity();
          }
          for(let i = 0; i < valuesPersonMoral.length; i++){ 
            this.filterForm.controls[valuesPersonMoral[i]].clearValidators();
            this.filterForm.controls[valuesPersonMoral[i]].updateValueAndValidity();
          }
          
        } else {
          this.filterForm.controls[name].setValue(value);
          this.int_mostrar_razon_social = true;
          for(let i = 0; i < valuesPersonFisica.length; i++){ 
            this.filterForm.controls[valuesPersonFisica[i]].clearValidators();
            this.filterForm.controls[valuesPersonFisica[i]].updateValueAndValidity();
          }
          for(let i = 0; i < valuesPersonMoral.length; i++){ 
            this.filterForm.controls[valuesPersonMoral[i]].setValidators([Validators.required]);
            this.filterForm.controls[valuesPersonMoral[i]].updateValueAndValidity();
          }
        }
        break;
      case 'prop_tipo_persona':
        if (value == 'Fisica') {
          this.prop_mostrar_razon_social = false;
          this.filterForm_step2.controls[name].setValue(value);
        } else {
          this.prop_mostrar_razon_social = true;
          this.filterForm_step2.controls[name].setValue(value);
        }
        break;
      case 'dro_requiere':
        if (value == 'Si') {
          setTimeout(() => { 
            this.filterForm_step4.enable();
          }, 500);
        } else {
          setTimeout(() => {
            this.filterForm_step4.disable();
            this.filterForm_step4.controls['dro_requiere'].enable();
          }, 500);
          console.log(this.filterForm_step4);
        }
        break;
    }
  }

  checkCopy(event: any, value) {
    let array_values = ['_rfc', '_calle', '_no_ext', '_no_int', '_colonia', '_localidad', '_mpio', '_cp'];
    switch (value) {
      case 'int_mostrar_razon_social':
        if (event.checked) {
          for (let i = 0; i < array_values.length; i++) {
            this.filterForm.controls['int_moral' + array_values[i]].setValue(this.filterForm.controls['int' + array_values[i]].value);
          }
        } else {
          for (let i = 0; i < array_values.length; i++) {
            this.filterForm.controls['int_moral' + array_values[i]].setValue('');
          }
        }
        break;
      case 'prop_mostrar_razon_social':
        if (event.checked) {
          for (let i = 0; i < array_values.length; i++) {
            this.filterForm_step2.controls['propmoral' + array_values[i]].setValue(this.filterForm_step2.controls['prop' + array_values[i]].value);
          }
        } else {
          for (let i = 0; i < array_values.length; i++) {
            this.filterForm_step2.controls['propmoral' + array_values[i]].setValue('');
          }
        }
        break;
      case 'copyall':
        array_values = ['_tipo_persona', '_nombre', '_apellidouno', '_apellidodos', '_curp', '_rfc', '_correo', '_cel', '_calle', '_no_ext', '_no_int', '_colonia', '_localidad', '_mpio', '_cp', '_rfc', '_calle', '_no_ext', '_no_int', '_colonia', '_localidad', '_mpio', '_cp'];
        if (event.checked) {
          if (this.filterForm.controls['int_tipo_persona'].value == 'Moral') {
            this.prop_mostrar_razon_social = true;
          } else {
            this.prop_mostrar_razon_social = false;
          }
          for (let i = 0; i < array_values.length; i++) {
            if (i < 15) {
              this.filterForm_step2.controls['prop' + array_values[i]].setValue(this.filterForm.controls['int' + array_values[i]].value);
            } else {
              this.filterForm_step2.controls['propmoral' + array_values[i]].setValue(this.filterForm.controls['int' + array_values[i]].value);
            }
          }
          this.filterForm_step2.controls['propmoral_rsocial'].setValue(this.filterForm.controls['int_moral_rsocial'].value);
        } else {
          for (let i = 0; i < array_values.length; i++) {
            if (i < 15) {
              this.filterForm_step2.controls['prop' + array_values[i]].setValue('');
            } else {
              this.filterForm_step2.controls['propmoral' + array_values[i]].setValue('');
            }
          }
          this.filterForm_step2.controls['propmoral_rsocial'].setValue('');
        }
        break;
    }
  }


  getRequisitosConstruccion() {
    this._campoDinamico.getRequisitosConstruccion(this.activatedRoute.snapshot.params.folio).subscribe(
      (res: any) => {
        this.filterForm_static.controls['m2_habitacional'].setValue(res[0].superficie_habitacional);
        this.filterForm_static.controls['m2_comercial'].setValue(res[0].superficie_comercial_servicios);
        this.filterForm_static.controls['m2_industrial'].setValue(res[0].superficie_industrial);
        this.filterForm_static.controls['m2_turistico'].setValue(res[0].superficie_turistico);
        this.filterForm_static.controls['m2_equipamiento'].setValue(res[0].superficie_equipamiento);
        this.filterForm_static.controls['m2_espacios_verdes'].setValue(res[0].superficie_espacios_verdes);
        this.filterForm_static.controls['m2_otro'].setValue(res[0].superficie_otro);
        //this.filterForm_static.controls['m2_a_construir'].setValue(res[0].superficie_otro);
        this.filterForm_static.controls['m2_a_demoler'].setValue(res[0].mdemolicion);
        console.log(res);
      },
      error => {
        console.log(error)
      }
    );
  }

  getNotarios() {
    this._campoDinamico.getNotarios().subscribe(
      (res: any) => {
        this.notarios_publicos = res;
      },
      error => {
      }
    );
  }


  generarProrroga() {
    if (this.formCompleted) {

      Swal.fire({
        icon: 'question',
        title: '¿Estás seguro de deseas generar una prórroga?',
        showCancelButton: true,
        confirmButtonText: `Generar`,
        cancelButtonText: `Cancelar`
      }).then((result) => {
        if (result.isConfirmed) {
          Swal.showLoading();
          this.generate();
        }
      })
    }
  }

  generate() {
    this.dialogRef = this._matDialog.open(EmitirProrrogaComponent, {
      panelClass: "emitir-form-dialog",
      width: "50%",
      height: "60%",
      disableClose: true,
      data: {
        action: "new",
        data: { folio: this.folio },
        type_lic: 2,
      },
    });
    this.dialogRef.afterClosed().subscribe((response) => {
      //console.log(response);
      if (response.status) {
        this.route2.navigate(["/tramites"]);
      }
      //
    });
  }

  addFile(value, index, data){
    if(value=='-' && this.arrayNameFiles.length>0){
        this.filterForm_step6.removeControl(this.arrayNameFiles[index]);
        this.arrayNameFiles.splice(index, 1);
        this.arrayValuesFiles.splice(index, 1);
        this.count -=1;
    }else if(value== '+'){
      this.count +=1;
      this.filterForm_step6.addControl('input'+this.count.toString(), new FormControl('', [Validators.required]));
      this.arrayNameFiles.push('input'+this.count.toString());
      this.arrayValuesFiles.push('file'+this.count.toString());
    }
  }

  async uploadFile2(file:File, index:number){
    await this.uploadFileService.uploadFileProrroga(file, this.folio, this.arrayValuesFiles[index])
     .then((response:any) => response.json()).then(data => {this.arrayValuesFiles_2[index] = data.data});
  }

  changeSelect(){
    if(this.filterForm_step3.value.fedatario_publico!='3'){
      this.filterForm_step3.controls['fedatario_publico_otro'].disable();
    }else{
      this.filterForm_step3.controls['fedatario_publico_otro'].enable();
    }
    if(this.filterForm_step3.value.tipo_dcto_propiedad!='5'){
      this.filterForm_step3.controls['tipo_dcto_propiedad_otro'].disable();
    }else{
      this.filterForm_step3.controls['tipo_dcto_propiedad_otro'].enable();
    }
  }
}
