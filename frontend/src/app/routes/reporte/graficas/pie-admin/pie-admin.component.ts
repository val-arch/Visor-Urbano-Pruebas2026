import { AfterViewInit, Component, ElementRef, HostListener, OnInit, ViewChild } from "@angular/core";
import { PieAdminService } from "./pie-admin.service";
import { FuseUtils } from "@fuse/utils/index";
import { fuseAnimations } from "@fuse/animations";
import { FormBuilder, FormControl, FormGroup, Validators } from "@angular/forms";
import { forkJoin } from 'rxjs';
import { MatTableDataSource } from "@angular/material/table";
import { MatPaginator } from "@angular/material/paginator";
import { MatSort } from "@angular/material/sort";

@Component({
    selector: "grafica-pie-admin",
    templateUrl: "./pie-admin.component.html",
    styleUrls: ["./pie-admin.component.scss"],
    animations: fuseAnimations,
})

export class PieAdminComponent implements OnInit , AfterViewInit  {
    constructor(public _pie_admin: PieAdminService, private fb: FormBuilder, private fb2: FormBuilder) {}
    displayedColumns: string[] = ['owner', 'folio', 'businessType', 'paymentDate', 'licenseStatus', 'address'];
    dataSource: MatTableDataSource<any>;
  
    @ViewChild(MatPaginator) paginator: MatPaginator;
    @ViewChild(MatSort) sort: MatSort;
    filtroGiros: FormGroup;

    loading: boolean = true;
    loadingAdvanced: boolean = true;
    data = [];
    timeline: boolean = false;
    dataLice = [];
    data2 = [];
    dataGiros = [];
    advancedData = [];
    filteredData = [];
    refrendos: any[];
licencias: any[];
totalValue:any;
    result;
    view: any[] = [700, 400];
    view3: any[] = [600, 400];
    barD = null;
    act;
    filtros_activos: boolean = false;
    // options
    gradient: boolean = false;
    showLegend: boolean = true;
    showLabels: boolean = true;
    isDoughnut: boolean = false;
    legendPosition: string = "right";
    muni = null;
    fil = null;
    filtro: FormGroup;
    rawData: any;

    // Data array for the chart
    colorScheme = { domain: ["#5AA454", "#E44D25", "#CFC0BB", "#7aa3e5"] };

    showXAxis = true; // Show X-axis
    showYAxis = true; // Show Y-axis

    showXAxisLabel = true; // Show label on the X-axis
    showYAxisLabel = true; // Show label on the Y-axis
    xAxisLabel = "Date"; // Label for the X-axis
    yAxisLabel = "Value"; // Label for the Y-axis
    view2: any[] = [window.innerWidth, window.innerHeight];
    @HostListener('window:resize', ['$event'])
    onResize(event) {
      this.width = event.target.innerWidth * 0.9; // Ajustar ancho al cambiar tamaño de ventana
    }

    width: number = window.innerWidth * 0.75; // 90% del ancho de la ventana
    height: number = window.innerHeight * 0.75; // altura fija de 600px
    width1: number = window.innerWidth * 0.75; // 90% del ancho de la ventana
    height1: number = window.innerHeight * 0.65; // altura fija de 600px
  
    ngOnInit(): void {
        
        //this._pie_admin.downloadExcel();
          
        this.loadAllData();
        let d = new Date();
        this.filtro = this.fb.group({
            f_inicio: [`${d.getFullYear()}-01-01`, Validators.required],
            f_fin: [`${d.getFullYear()}-12-31`, Validators.required],
            tipo_licencia: ["Todas", Validators.required],
            origen_licencia: ["Todas", Validators.required],
        });
        this.getData();
        this.getMostGiros();
        this.getAdvancedpie();
        this.processData();
        this.filtroGiros =this.fb2.group({
            giroFechaInicio: new FormControl(),
            giroFechaFin: new FormControl()
        });
    }
    loadAllData() {
        forkJoin({
         
          licencias: this._pie_admin.getLicencias()
        }).subscribe(({  licencias }) => {
            this.rawData = (licencias as any[] || []);
            this.rawData.sort((a, b) => {
                const dateA = new Date(a.inicio_semana);
                const dateB = new Date(b.inicio_semana);
                return dateA.getTime() - dateB.getTime();
            });
    
          console.log("Combined Raw Data:", this.rawData);
          this.processData();
        }, error => {
          console.error("Error fetching data:", error);
        });
      }
    processData() {
        setTimeout(() => {
            if (!this.rawData || this.rawData.length === 0) {
                console.log(this.rawData);
                console.log("Data is not loaded yet or is empty.");
                return;
            }
            const groupedData = {};
            for (const item of this.rawData) {
                const key = item.nombre_municipio;
                if (!groupedData[key]) {
                    groupedData[key] = { name: key, series: [] };
                }
                groupedData[key].series.push({
                    name: item.inicio_semana,
                    value: item.total_licencias,
                    // Convert date string to date object for sorting purposes
                    date: new Date(item.inicio_semana),
                });
            }

            // Now sort the series within each group by date
            for (const key in groupedData) {
                groupedData[key].series.sort((a, b) => {
                    return a.date - b.date; // Sorting by the date object
                });
                // Remove the temporary date object not needed for display
                groupedData[key].series = groupedData[key].series.map(
                    ({ name, value }) => ({ name, value })
                );
            }

            this.dataLice = Object.values(groupedData);
            this.filteredData = [...this.dataLice]; // Make a copy if necessary
            console.log(this.dataLice, "Sorted data");
        }, 5000); // Delay in milliseconds
    }
    resetFilters(): void {
        this.filteredData = [...this.dataLice]; // Reset to show all data
    }
    getAdvancedpie() {
        this._pie_admin.getAdvancedPie().subscribe(
            (resp: any) => {
                console.log(resp);
                if (resp.data) {
                    this.advancedData = resp;
                    this.loadingAdvanced = false;
                }
            },
            (e) => console.error(e)
        );
    }

    getGirosMasSolicitados() {
        const startDate = this.filtroGiros.value.giroFechaInicio;
        const endDate = this.filtroGiros.value.giroFechaFin;
        console.log( this.filtroGiros);
        this._pie_admin.getMostGiros(startDate, endDate).subscribe(
            (resp: any) => {
                if (resp) {
                    this.dataGiros = resp;
                    this.data2 = this.dataGiros.map((giro) => ({
                        name: giro.SCIAN,
                        value: giro.uso,
                    }));
                    console.log("Chart data:", this.data);
                    this.loading = false;
                } else {
                    console.error("No data available");
                }
            },
            (e) => {
                console.error("Error fetching data:", e);
            }
        );
       
    }
    getMostGiros() {
        this.barD = null;
        this._pie_admin.getMostGiros('','').subscribe(
            (resp: any) => {
                if (resp) {
                    this.dataGiros = resp;
                    this.data2 = this.dataGiros.map((giro) => ({
                        name: giro.SCIAN,
                        value: giro.uso,
                    }));
                    console.log("Chart data:", this.data);
                    this.loading = false;
                } else {
                    console.error("No data available");
                }
            },
            (e) => {
                console.error("Error fetching data:", e);
            }
        );
    }

    getData() {
        this.barD = null;
        console.log(this.filtro.value);
        this._pie_admin.getData(this.filtro.value).subscribe(
            (resp: any) => {
                if (resp.data) {
                    const resultMap = new Map();
                    let totalSum = 0;  // Variable to keep track of the total sum of 'value'
    
                    resp.data.forEach(entry => {
                        const currentEntry = resultMap.get(entry.name) || { extra: 0, name: entry.name, value: 0 };
                        currentEntry.extra += entry.extra; // Update extra if necessary
                        currentEntry.value += entry.value;
                        resultMap.set(entry.name, currentEntry);
    
                        totalSum += entry.value;  // Accumulate the value into totalSum
                    });
    
                    this.data = Array.from(resultMap.values());
                    this.totalValue = totalSum;  // Store the total sum in a component property
    
                    console.log("Chart data:", this.data);
                    console.log("Total sum of values:", this.totalValue);
                    this.loading = false;
                } else {
                    console.error("No data available");
                }
            },
            (e) => {
                console.error("Error fetching data:", e);
            }
        );
    }
    
    onSelectMunicipio(event: any): void {
        console.log("Chart event:", event);
        // Filter the data to only include the selected municipality

        this.filteredData = this.dataLice.filter((municipality) => {
            // Log each municipality name during the filtering to check what's being processed
            console.log(municipality.name, event);
            return municipality.name === event;
        });
        console.log(this.filteredData);
    }
    async onSelect(data) {
        console.log(data);
        if (!data.extra) {
          data = this.data.find(obj => obj.name === data.name);
          if (!data) return;
        }
        this.act = data;
        await this._pie_admin.getDataMunicipio(data.extra, this.filtro.value).toPromise().then((res: any) => {
          this.dataSource = new MatTableDataSource(res);
          this.dataSource.paginator = this.paginator;
          this.dataSource.sort = this.sort;
        }).catch((e) => console.error(e));
      }
    
      ngAfterViewInit() {
        if (this.dataSource) {
          this.dataSource.paginator = this.paginator;
          this.dataSource.sort = this.sort;
        }
      }
    descargar (){
        this._pie_admin.downloadExcel();
    }
}
