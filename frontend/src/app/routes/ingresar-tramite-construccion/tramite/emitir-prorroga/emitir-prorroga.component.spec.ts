import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { EmitirProrrogaComponent } from './emitir-prorroga.component';

describe('EmitirProrrogaComponent', () => {
  let component: EmitirProrrogaComponent;
  let fixture: ComponentFixture<EmitirProrrogaComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ EmitirProrrogaComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(EmitirProrrogaComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
