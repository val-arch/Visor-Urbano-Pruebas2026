import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DialogAddCampoTemplateComponent } from './dialog-add-campo-template.component';

describe('DialogAddCampoTemplateComponent', () => {
  let component: DialogAddCampoTemplateComponent;
  let fixture: ComponentFixture<DialogAddCampoTemplateComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DialogAddCampoTemplateComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DialogAddCampoTemplateComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
