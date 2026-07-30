import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { GraficasConstruccionComponent } from './graficas-construccion.component';

describe('GraficasConstruccionComponent', () => {
  let component: GraficasConstruccionComponent;
  let fixture: ComponentFixture<GraficasConstruccionComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ GraficasConstruccionComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(GraficasConstruccionComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
