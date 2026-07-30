import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TramiteEditComponent } from './tramite-edit.component';

describe('TramiteEditComponent', () => {
  let component: TramiteEditComponent;
  let fixture: ComponentFixture<TramiteEditComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TramiteEditComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TramiteEditComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
