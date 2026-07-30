import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { EmitirRefrendoHistoricoComponent } from './emitir-refrendo-historico.component';

describe('EmitirRefrendoHistoricoComponent', () => {
  let component: EmitirRefrendoHistoricoComponent;
  let fixture: ComponentFixture<EmitirRefrendoHistoricoComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ EmitirRefrendoHistoricoComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(EmitirRefrendoHistoricoComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
