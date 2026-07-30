import { HttpClient } from '@angular/common/http';
import { Component, Inject, OnInit } from '@angular/core';
import { NgForm } from '@angular/forms';
import { MatInputModule } from '@angular/material/input';
import { FormControl } from '@angular/forms';
import { Observable } from 'rxjs';
import { map, startWith } from 'rxjs/operators';
import Swal from 'sweetalert2';
import { environment } from '@env/environment';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';

@Component({
  selector: 'app-form-fichas',
  templateUrl: './form-fichas.component.html',
  styleUrls: ['./form-fichas.component.scss']
})
export class FormFichasComponent implements OnInit {
code: string[];

constructor(private httpClient: HttpClient, public matDialogRef: MatDialogRef<FormFichasComponent>,
        @Inject(MAT_DIALOG_DATA) private _data: any,) {
        this.readJSON();
    }
    searchControl = new FormControl();
    filteredOptions: Observable<string[]>;
    ngOnInit() {
        this.readJSON();
      this.filteredOptions = this.searchControl.valueChanges.pipe(
        startWith(''),
        map(value => this._filter(value))
      );
    }
  
    private _filter(value: string): string[] {
        const filterValue = value.toLowerCase();
        if (this.code) {
          return this.code.filter(option => option.toLowerCase().includes(filterValue));
        } else {
          return [];
        }
      }
      readJSON() {
        this.httpClient.get('assets/estados.json').subscribe((data: any) => {
          this.code = data.map(obj => obj.nombre);
        });
    }
  
    displayFn(option: string): string {
      return option ? option : '';
    }
  
  sectores = [
    'Ciudadanía',
    'Academia',
    'Startup',
    'Empresa',
    'Cámara',
    'Gobierno',
    'Medio de comunicación',
    'Organización de la Sociedad Civil'
  ];
 
  usos = [
    { label: 'Proyecto inmobiliario', checked: false },
    { label: 'Investigación o análisis', checked: false },
    { label: 'Nota o artículo', checked: false },
    { label: 'Programa o Política Pública', checked: false },
    { label: 'Conocimiento general', checked: false }
  ];

  edades = Array.from({ length: 100 }, (_, i) => i + 18);

  ciudades = [
    'EEUU',
    'Canadá',
    'Latinoamérica',
    'Europa',
    'África',
    'Asia'
  ];

  selectedSector: string;
  selectedEdad: number;
  selectedCiudad: string;
  nombre: string;
  correo: string;

  submitForm(form: NgForm): void {
    if (form.valid) {
      const formData = {
        sector: this.selectedSector,
        usos: this.usos.filter(uso => uso.checked).map(uso => uso.label),
        edad: this.selectedEdad,
        ciudad: this.selectedCiudad,
        nombre: this.nombre,
        correo: this.correo,
    
      };

      this.httpClient.post(`${environment.SERVER_ORIGIN}ficha_tecnica_consulta`, formData).subscribe((response) => {
        console.log(response['data'].id);

        this.matDialogRef.close(response['data'].id);
     
      });
      

    }else{

        Swal.fire({
            title: '¡Formulario Incompleto!',
            text: 'Por favor llene todo el formulario antes de continuar.',
            icon: 'warning',
            confirmButtonText: 'Ok'
        });

    }
  }

  closeDialog(): void {
    this.matDialogRef.close();
  }
  
  
  
}
