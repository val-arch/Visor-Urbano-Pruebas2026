import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SeguimientoLicenciaComponent } from './seguimiento-licencia.component';

describe('SeguimientoLicenciaComponent', () => {
  let component: SeguimientoLicenciaComponent;
  let fixture: ComponentFixture<SeguimientoLicenciaComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SeguimientoLicenciaComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SeguimientoLicenciaComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
