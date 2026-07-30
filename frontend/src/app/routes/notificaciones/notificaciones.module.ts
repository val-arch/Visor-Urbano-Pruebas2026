import { NgModule } from '@angular/core';
import { RouterModule } from '@angular/router';


import { CommonModule } from '@angular/common';

import { MatToolbarModule } from '@angular/material/toolbar';
import { MatButtonModule } from '@angular/material/button';
import { MatIconModule } from '@angular/material/icon';
import { FlexLayoutModule, GridModule } from '@angular/flex-layout';
import { MatGridListModule } from '@angular/material/grid-list';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';

import { NgxCopilotModule } from  'ngx-copilot';
import { NgxDatatableModule } from '@swimlane/ngx-datatable';
import { MtxGridModule } from '@ng-matero/extensions/data-grid';
// import { ScrollToModule } from '@nicky-lenaers/ngx-scroll-to';

// import { PdfViewerModule } from 'ng2-pdf-viewer';

import { MatListModule } from '@angular/material/list';
import { MaterialModule } from '../../material.module';
import { ListComponent } from './list/list.component';
import { NotificacionesComponent } from './notificaciones.component';
import { NotificacionesDialogComponent } from './notificaciones-dialog/notificaciones-dialog.component';

import { DetalleComponent } from './detalle/detalle.component';
import { SafePipe } from './detalle/safe.pipe';

const routes = [
  {
      path     : 'notificaciones/list', component: NotificacionesComponent
  }, 
   //   path     : 'notificaciones/detalle/:id/:type',
  {
      path :'notificaciones/detalle/:folio/:type/:id',
      component: DetalleComponent
  },
]
@NgModule({
  declarations: [
  ListComponent,
  NotificacionesComponent,
  NotificacionesDialogComponent,
  DetalleComponent,
  SafePipe],
  imports: [
    RouterModule.forChild(routes),
    CommonModule,
    MatToolbarModule,
    MatButtonModule,
    MatIconModule,
    MatGridListModule,
    MtxGridModule,
    NgxCopilotModule,
    NgxDatatableModule,
    BrowserAnimationsModule,
    FlexLayoutModule,
    MaterialModule,
    MatListModule,
    // ScrollToModule.forRoot(),
    // PdfViewerModule

  ],
  exports: [
    
    MatToolbarModule,
    MatButtonModule,
    MatIconModule,
    MatGridListModule,
    BrowserAnimationsModule,
    FlexLayoutModule,
  ]
})

export class NotificacionesModule { }