import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { UtilidadesMunicipiosComponent } from './utilidades-municipios.component';

describe('UtilidadesMunicipiosComponent', () => {
  let component: UtilidadesMunicipiosComponent;
  let fixture: ComponentFixture<UtilidadesMunicipiosComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ UtilidadesMunicipiosComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(UtilidadesMunicipiosComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
