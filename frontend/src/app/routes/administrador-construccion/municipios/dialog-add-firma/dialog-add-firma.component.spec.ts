import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DialogAddFirmaComponent } from './dialog-add-firma.component';

describe('DialogAddFirmaComponent', () => {
  let component: DialogAddFirmaComponent;
  let fixture: ComponentFixture<DialogAddFirmaComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DialogAddFirmaComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DialogAddFirmaComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
