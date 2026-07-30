export class UserAdmin {
        public id?: number;
        public name: string;
        public apellido_p: string;
        public apellido_m: string;
        public celular: string;
        public email: string;
        public rfc: string;
        public curp: string;
        public password: string;
        public role: number;
    constructor(
        UserAdmin
        ){

            this.id = UserAdmin.id || 0;
            this.name = UserAdmin.name || '';
            this.apellido_p = UserAdmin.apellido_p || '';
            this.apellido_m = UserAdmin.apellido_m || '';
            this.celular = UserAdmin.celular || '';
            this.email = UserAdmin.email || '';
            this.rfc = UserAdmin.rfc || '';
            this.curp = UserAdmin.curp || '';
            this.password = UserAdmin.password || '';
            this.role = UserAdmin.role || '';

        }
}