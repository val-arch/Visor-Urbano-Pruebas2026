import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AnalisisComercialComponent } from './analisis-comercial.component';

describe('AnalisisComercialComponent', () => {
  let component: AnalisisComercialComponent;
  let fixture: ComponentFixture<AnalisisComercialComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AnalisisComercialComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AnalisisComercialComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
