
export class ConditionPerson {
    type_user: number;
    permiso_dir: number;
    constructor() {
        this.type_user   = 0;
        this.permiso_dir = 0;
        if (localStorage.getItem('usr') == null) {
            this.type_user = 0;
        }else{
            this.permiso_dir = JSON.parse(localStorage.getItem('usr')).permiso_dir;
            this.type_user   = JSON.parse(localStorage.getItem('usr')).user_type;
        }
     
    }
}