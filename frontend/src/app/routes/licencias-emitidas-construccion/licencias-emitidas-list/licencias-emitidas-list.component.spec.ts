import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { LicenciasEmitidasListComponent } from './licencias-emitidas-list.component';

describe('LicenciasEmitidasListComponent', () => {
  let component: LicenciasEmitidasListComponent;
  let fixture: ComponentFixture<LicenciasEmitidasListComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ LicenciasEmitidasListComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(LicenciasEmitidasListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
