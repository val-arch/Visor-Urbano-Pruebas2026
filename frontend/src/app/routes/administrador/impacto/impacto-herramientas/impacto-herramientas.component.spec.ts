import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ImpactoHerramientasComponent } from './impacto-herramientas.component';

describe('CapasComponent', () => {
  let component: ImpactoHerramientasComponent;
  let fixture: ComponentFixture<ImpactoHerramientasComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ImpactoHerramientasComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ImpactoHerramientasComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
