import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DialogMunicipioComponent } from './dialog-municipio.component';

describe('DialogMunicipioComponent', () => {
  let component: DialogMunicipioComponent;
  let fixture: ComponentFixture<DialogMunicipioComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DialogMunicipioComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DialogMunicipioComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
