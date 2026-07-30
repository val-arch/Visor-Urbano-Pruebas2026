import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DialogComercialComponent } from './dialog-comercial.component';

describe('DialogComercialComponent', () => {
  let component: DialogComercialComponent;
  let fixture: ComponentFixture<DialogComercialComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DialogComercialComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DialogComercialComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
