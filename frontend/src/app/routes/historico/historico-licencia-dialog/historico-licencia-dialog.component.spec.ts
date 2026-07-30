import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { HistoricoLicenciaDialogComponent } from './historico-licencia-dialog.component';

describe('HistoricoLicenciaDialogComponent', () => {
  let component: HistoricoLicenciaDialogComponent;
  let fixture: ComponentFixture<HistoricoLicenciaDialogComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ HistoricoLicenciaDialogComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(HistoricoLicenciaDialogComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
