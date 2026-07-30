import { Component, Inject, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators} from '@angular/forms';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';

import { UseradminService } from 'app/services/administrador/usuarios/useradmin.service';
import Swal from 'sweetalert2';
import { UserAdmin } from '../../../../models/administrador/useradmin';
import { UserroleService } from 'app/services/administrador/usuarios/userrole.service';
import { LocalStorageService } from '@shared/services/storage.service';



@Component({
    templateUrl: "./dialog-user.component.html",
    styleUrls: ["./dialog-user.component.scss"],
})
export class DialogUserComponent implements OnInit {
    btnCss = {
        "background-color": "#70CE68",
        color: "white",
    };
    user: UserAdmin;
    action: string;
    userForm: FormGroup;
    dialogTitle: string;
    errors = null;
    list = [];
    total = 0;
    isLoading = true;
    page = 0;

    constructor(
        private _role: UserroleService,
        public matDialogRef: MatDialogRef<DialogUserComponent>,
        @Inject(MAT_DIALOG_DATA) private _data: any,
        private _formBuilder: FormBuilder,
        private userService: UseradminService,
        private _store: LocalStorageService
    ) {
        this.action = _data.action;

        if (this.action === "edit") {
            this.dialogTitle = "Editar usuario";
            this.user = this._data.user;
            this.userForm = this.editeuserForm();
        } else {
            this.dialogTitle = "Crear usuario";
            this.user = new UserAdmin({});
            this.userForm = this.createuserForm();
        }
    }

    ngOnInit(): void {
        this.getData();
    }
    createuserForm(): FormGroup {
        return this._formBuilder.group({
            id: [this.user.id],
            name: [this.user.name, Validators.required],
            apellido_p: [this.user.apellido_p, Validators.required],
            apellido_m: [this.user.apellido_m],
            celular: [
                this.user.celular,
                [
                    Validators.required,
                    Validators.minLength(10),
                    Validators.maxLength(10),
                ],
            ],
            email: [this.user.email, [Validators.required, Validators.email]],
            rfc: [
                this.user.rfc,
                [
                    Validators.required,
                    Validators.minLength(12),
                    Validators.maxLength(13),
                ],
            ],
            curp: [
                this.user.curp,
                [
                    Validators.required,
                    Validators.maxLength(18),
                    Validators.minLength(18),
                ],
            ],
            password: [this.user.password, Validators.required],
            role: [this.user.role, Validators.required],
        });
    }
    editeuserForm(): FormGroup {
        return this._formBuilder.group({
            id: [this.user.id],
            name: [this.user.name, Validators.required],
            apellido_p: [this.user.apellido_p, Validators.required],
            apellido_m: [this.user.apellido_m],
            celular: [
                this.user.celular,
                [
                    Validators.required,
                    Validators.minLength(10),
                    Validators.maxLength(10),
                ],
            ],
            email: [this.user.email, [Validators.required, Validators.email]],
            rfc: [
                this.user.rfc,
                [
                    Validators.required,
                    Validators.minLength(12),
                    Validators.maxLength(13),
                ],
            ],
            curp: [
                this.user.curp,
                [
                    Validators.required,
                    Validators.maxLength(18),
                    Validators.minLength(18),
                ],
            ],
            password: [this.user.password, Validators.required],
        });
    }

    saveUser() {
        if (this.action == "edit") {
            this.userForm.removeControl("role");
            this.userService
                .storeUsers(this.userForm.value)
                .subscribe((resp) => {
                    Swal.fire({
                        title: "¡Éxito!",
                        text: "Guardado correctamente!",
                        icon: "success",
                        confirmButtonText: "Ok",
                    });
                    this.matDialogRef.close();
                });
        } else {
            this.userService.storeUsers(this.userForm.value).subscribe(
                (result) => {
                    Swal.fire({
                        title: "¡Éxito!",
                        text: "Guardado correctamente!",
                        icon: "success",
                        confirmButtonText: "Ok",
                    });
                    this.matDialogRef.close();
                },
                (error) => {
                    this.errors = error;
                    Swal.fire({
                        title: "Ops",
                        text: this.errors.error.error,
                        icon: "warning",
                        confirmButtonText: "Ok",
                    });
                    console.log();
                }
            );
        }
    }

    getData() {
        let user = this._store.get("usr");
        console.log(user)
        if (user.rol == 5) {
            this.isLoading = true;
            this.total = 5;
            this.isLoading = this.isLoading = false;
            this.list = [
                {
                    id: 1,
                    name: "ciudadano",
                    descripcion: null,
                    id_municipio: 0,
                    deleted_at: null,
                },
                {
                    id: 2,
                    name: "ventanilla",
                    descripcion: null,
                    id_municipio: 0,
                    deleted_at: null,
                },
                {
                    id: 3,
                    name: "revisor",
                    descripcion: null,
                    id_municipio: 0,
                    deleted_at: null,
                },
                {
                    id: 4,
                    name: "director",
                    descripcion: null,
                    id_municipio: 0,
                    deleted_at: null,
                },
                {
                    id: 5,
                    name: "admin",
                    descripcion: null,
                    id_municipio: 0,
                    deleted_at: null,
                },
                {
                    id: 6,
                    name: "tecnico",
                    descripcion: null,
                    id_municipio: 0,
                    deleted_at: null,
                },
            ];
            return;
        }

        this._role.getRoles(this.page).subscribe(
            (res: any) => {
                this.total = res.total;
                this.list = res.data;
                this.list = this.list.filter((role) => role.name != "admin");

                console.log(this.list);
                this.isLoading = false;
            },
            (error) => {
                this.isLoading = false;
                console.log(error);
            }
        );
    }
}
