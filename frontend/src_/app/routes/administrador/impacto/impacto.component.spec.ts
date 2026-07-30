import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ImpactoComponent } from './impacto.component';

describe('ZonificacionComponent', () => {
  let component: ImpactoComponent;
  let fixture: ComponentFixture<ImpactoComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ImpactoComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ImpactoComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
