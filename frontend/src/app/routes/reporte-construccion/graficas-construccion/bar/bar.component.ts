import { Component, OnInit, Input, SimpleChanges } from "@angular/core";
import { MatDialog } from "@angular/material/dialog";
import { BarService } from "./bar.service";
import { DetalleDialogComponent } from "../detalle-dialog/detalle-dialog.component";
import { SettingsService } from '@core/settings.service';

@Component({
  selector: 'grafica-bar',
  templateUrl: './bar.component.html',
  styleUrls: ['./bar.component.scss']
})
export class BarComponent implements OnInit {

  loading: boolean = true;
  data = [];
  @Input() extra = null;
  @Input() muni = null;
  @Input() fil = {f_inicio: `${new Date().getFullYear()}-01-01`,
  f_fin: `${new Date().getFullYear()}-12-31`};
  result;
  view: any[] = [1000, 400];

  // options
  showXAxis = true;
  showYAxis = true;
  gradient = false;
  showLegend = true;

  showXAxisLabel = true;
  xAxisLabel = "Mes";
  showYAxisLabel = true;
  yAxisLabel = "Licencias emitidas";
  dialogRef;

  colorScheme = {
    domain: ["#003E76", "#ABE2F5", "#A0D89B"],
  };
  constructor(public _bar: BarService, private _matDialog: MatDialog,private _settings: SettingsService) {}

  ngOnInit(): void {
    if (this.extra == null) {
      this._bar.getData().subscribe((r: any) => {
        if (r.data) {
          console.log(this.data);
          this.data = r.data;
          this.loading = false;
        }
      },
      (e) => console.log(e));
    } else {
      this.data = this.extra;
      this.loading = false;
    }
  }

  onSelect(data) {
    console.log(data);
    if (!data.extra) {
      data = this.data.find((obj) => obj.name == data);
      if (!data) return;
    }
    let d = {
      fecha_inicio: this.fil.f_inicio ?? 0,
      fecha_fin: this.fil.f_fin ?? 0,
      mes: data.extra,
      municipio:0,
    };
    if(this.muni){
      d.municipio = this.muni.extra;
    }else{
      d.municipio = this._settings.user.id_municipio
    }
    this._bar.getListado(d).toPromise().then((res: any) => {
      this.dialogRef = this._matDialog.open(DetalleDialogComponent, {
        panelClass: "detalle-form-dialog",
        data: {
          data: res,
          action: "list",
        },
        width: "70%",
      });
      this.dialogRef.afterClosed().subscribe((response: any) => {});
    }).catch((e) => console.error(e));
      // console.log(d);
    }
}