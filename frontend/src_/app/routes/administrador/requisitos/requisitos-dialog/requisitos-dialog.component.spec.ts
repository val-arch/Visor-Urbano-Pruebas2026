import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { RequisitosDialogComponent } from './requisitos-dialog.component';

describe('RequisitosDialogComponent', () => {
  let component: RequisitosDialogComponent;
  let fixture: ComponentFixture<RequisitosDialogComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ RequisitosDialogComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(RequisitosDialogComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
