import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { RequisitosListaComponent } from './requisitos-lista.component';

describe('RequisitosListaComponent', () => {
  let component: RequisitosListaComponent;
  let fixture: ComponentFixture<RequisitosListaComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ RequisitosListaComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(RequisitosListaComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
