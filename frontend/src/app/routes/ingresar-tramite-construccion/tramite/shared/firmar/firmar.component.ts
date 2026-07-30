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
import Swal from "sweetalert2";
import { fuseAnimations } from "@fuse/animations";
import { Observable } from "rxjs";
import { FuseUtils } from "@fuse/utils/index";
import { FirmaElectronicaService } from '../../../../../services/tramite/firma-electronica.service';

@Component({
  templateUrl: './firmar.component.html',
  styleUrls: ['./firmar.component.scss', '../../formulario-vu.scss'],
  animations: fuseAnimations,
})
export class FirmarComponent implements OnInit {
  public action: string;
  public dialogTitle: string;
  public firmaForm: FormGroup;
  data;
  key;
  cer;
  error='';
  loader;
  constructor(public matDialogRef: MatDialogRef<FirmarComponent>,
    @Inject(MAT_DIALOG_DATA) private _data: any,
    private fb: FormBuilder,
    private _firmaService: FirmaElectronicaService
    ) {
      this.data=_data;
     }

  ngOnInit(): void {

    this.firmaForm = this.fb.group({
      key: ['', Validators.required],
      cer: ['', Validators.required],
      password: ['', Validators.required]
    });
    
  }

  fileUp(event,type){
    if(type=='key'){
      this.key = event.target.files[0];
    }else{
      this.cer = event.target.files[0];
    }
  }

  firmar(){
    console.log(this.firmaForm);
    if(this.firmaForm.valid){
      const formData = new FormData();
        formData.append("file_cer", this.cer);
        formData.append("file_key", this.key);
        formData.append("password", this.firmaForm.value.password);
        formData.append("curp", this.data.curp);
        formData.append("cadena", this.data.cadena);
        formData.append("id_tramite", this.data.id_tramite);
        formData.append("parte_tramite", this.data.parte_tramite);
        Swal.showLoading(); 
        this._firmaService.firmarConstruccion(formData).subscribe((r:any)=>{
          this.error = '';
          Swal.close();
          this.matDialogRef.close({cadena:r.data.hash,status:true});

          console.log(r);
        },e =>{
          Swal.close();
          this.error = e.error.error;
          console.error(e.error.error);
        });
        
    }
  }

}

