import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { IniciarTramiteComponent } from './iniciar-tramite.component';

describe('IniciarTramiteComponent', () => {
  let component: IniciarTramiteComponent;
  let fixture: ComponentFixture<IniciarTramiteComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ IniciarTramiteComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(IniciarTramiteComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
