import { Component, OnInit } from "@angular/core";
import { TokenService } from '../../../core/authentication/token.service';
@Component({
    selector: "app-graficas",
    templateUrl: "./graficas.component.html",
    styleUrls: ["./graficas.component.scss"],
})
export class GraficasComponent implements OnInit {
    role;
    constructor(public _token:TokenService) {
      this.role = this._token.get().role;
    }

    ngOnInit(): void {

    }

 
}
