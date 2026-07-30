import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DialogHistorialConstruccionComponent } from './dialog-historial-construccion.component';

describe('DialogHistorialConstruccionComponent', () => {
  let component: DialogHistorialConstruccionComponent;
  let fixture: ComponentFixture<DialogHistorialConstruccionComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DialogHistorialConstruccionComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DialogHistorialConstruccionComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
