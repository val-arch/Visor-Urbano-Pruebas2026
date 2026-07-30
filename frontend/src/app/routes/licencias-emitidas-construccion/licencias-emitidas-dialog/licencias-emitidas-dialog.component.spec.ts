import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { LicenciasEmitidasDialogComponent } from './licencias-emitidas-dialog.component';

describe('LicenciasEmitidasDialogComponent', () => {
  let component: LicenciasEmitidasDialogComponent;
  let fixture: ComponentFixture<LicenciasEmitidasDialogComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ LicenciasEmitidasDialogComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(LicenciasEmitidasDialogComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
