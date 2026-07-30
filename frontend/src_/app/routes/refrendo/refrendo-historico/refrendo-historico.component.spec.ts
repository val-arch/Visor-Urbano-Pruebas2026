import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { RefrendoHistoricoComponent } from './refrendo-historico.component';

describe('RefrendoHistoricoComponent', () => {
  let component: RefrendoHistoricoComponent;
  let fixture: ComponentFixture<RefrendoHistoricoComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ RefrendoHistoricoComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(RefrendoHistoricoComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
