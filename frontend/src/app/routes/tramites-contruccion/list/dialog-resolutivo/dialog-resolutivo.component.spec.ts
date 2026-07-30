import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DialogResolutivoComponent } from './dialog-resolutivo.component';

describe('DialogResolutivoComponent', () => {
  let component: DialogResolutivoComponent;
  let fixture: ComponentFixture<DialogResolutivoComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DialogResolutivoComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DialogResolutivoComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
