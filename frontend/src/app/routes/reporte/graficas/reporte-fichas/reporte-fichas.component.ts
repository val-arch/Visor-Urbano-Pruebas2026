import { HttpClient } from "@angular/common/http";
import { Component, HostListener, OnInit } from "@angular/core";

@Component({
    selector: "app-reporte-fichas",
    templateUrl: "./reporte-fichas.component.html",
    styleUrls: ["./reporte-fichas.component.scss"],
})
export class ReporteFichasComponent implements OnInit {
    colorScheme = {
        domain: ["#5AA454", "#A10A28", "#C7B42C", "#AAAAAA"],
    };
    gradient: boolean = false;
    data: any[];
    datosFiltrados: any[];
    totalFichas: number = 0;
    fechaInicio: string;
    fechaFin: string;
    lineChartData: any[];
    view: any[] = [window.innerWidth, window.innerHeight];

    width: number = window.innerWidth * 0.75; // 90% del ancho de la ventana
    height: number = 600; // altura fija de 600px
  
    @HostListener('window:resize', ['$event'])
    onResize(event) {
      this.width = event.target.innerWidth * 0.9; // Ajustar ancho al cambiar tamaño de ventana
    }
    constructor(private http: HttpClient) {}
    ngOnInit() {
        this.http
            .get("https://api-visorurbano.jalisco.gob.mx/fichas-tecnicas-adm")
            .subscribe((res: any) => {
                console.log(res,'---');
                this.data = res.dias.map((item) => ({
                    name: `${item.dia}/${item.mes}/${item.anio}`,
                    value: item.fichas,
                }));
                this.datosFiltrados = [...this.data];
                this.calcularTotalFichas();
            });
    }
    filtrarDatos() {
        if (!this.fechaInicio || !this.fechaFin) {
            this.datosFiltrados = [...this.data];
        } else {
            this.datosFiltrados = this.data.filter((d) => {
                const [day, month, year] = d.name.split('/').map(Number);
                const fecha = new Date(year, month - 1, day); // create date from components
    
                const [startYear, startMonth, startDay] = this.fechaInicio.split('-').map(Number);
                const inicio = new Date(startYear, startMonth - 1, startDay);
    
                const [endYear, endMonth, endDay] = this.fechaFin.split('-').map(Number);
                const fin = new Date(endYear, endMonth - 1, endDay);
    
                inicio.setHours(0, 0, 0, 0);
                fin.setHours(23, 59, 59, 999);
    
                return fecha >= inicio && fecha <= fin;
            }).sort((a, b) => {
                const [aDay, aMonth, aYear] = a.name.split('/').map(Number);
                const aDate = new Date(aYear, aMonth - 1, aDay);
                const [bDay, bMonth, bYear] = b.name.split('/').map(Number);
                const bDate = new Date(bYear, bMonth - 1, bDay);
    
                return aDate.getTime() - bDate.getTime(); // Using getTime() to get milliseconds since Unix epoch
            });
        }
        this.calcularTotalFichas();
    }
    
    
    

    calcularTotalFichas() {
        this.totalFichas = this.datosFiltrados.reduce(
            (acc, curr) => acc + curr.value,
            0
        );
    }
}
