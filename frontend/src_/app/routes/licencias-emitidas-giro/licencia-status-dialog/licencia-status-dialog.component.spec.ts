import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { LicenciaStatusDialogComponent } from './licencia-status-dialog.component';

describe('LicenciaStatusDialogComponent', () => {
  let component: LicenciaStatusDialogComponent;
  let fixture: ComponentFixture<LicenciaStatusDialogComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ LicenciaStatusDialogComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(LicenciaStatusDialogComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
