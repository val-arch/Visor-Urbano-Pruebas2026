import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FormFichasComponent } from './form-fichas.component';

describe('FormFichasComponent', () => {
  let component: FormFichasComponent;
  let fixture: ComponentFixture<FormFichasComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FormFichasComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FormFichasComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
