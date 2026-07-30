import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DialogAddTipoTramiteComponent } from './dialog-add-tipo-tramite.component';

describe('DialogAddTipoTramiteComponent', () => {
  let component: DialogAddTipoTramiteComponent;
  let fixture: ComponentFixture<DialogAddTipoTramiteComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DialogAddTipoTramiteComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DialogAddTipoTramiteComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
