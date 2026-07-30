export class User {
    constructor(
        public nombre: string,
        public apellidoP: string,
        public email: string,
        public password: string,
        public celular: string,
        public curp: string,
        public apellidoM?: string,
        
        ){}
}