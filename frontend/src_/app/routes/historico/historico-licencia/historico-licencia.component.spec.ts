import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { HistoricoLicenciaComponent } from './historico-licencia.component';

describe('HistoricoLicenciaComponent', () => {
  let component: HistoricoLicenciaComponent;
  let fixture: ComponentFixture<HistoricoLicenciaComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ HistoricoLicenciaComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(HistoricoLicenciaComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
