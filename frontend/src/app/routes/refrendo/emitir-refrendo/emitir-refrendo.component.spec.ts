import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { EmitirRefrendoComponent } from './emitir-refrendo.component';

describe('EmitirRefrendoComponent', () => {
  let component: EmitirRefrendoComponent;
  let fixture: ComponentFixture<EmitirRefrendoComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ EmitirRefrendoComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(EmitirRefrendoComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
