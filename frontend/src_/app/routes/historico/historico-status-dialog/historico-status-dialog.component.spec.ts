import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { HistoricoStatusDialogComponent } from './historico-status-dialog.component';

describe('HistoricoStatusDialogComponent', () => {
  let component: HistoricoStatusDialogComponent;
  let fixture: ComponentFixture<HistoricoStatusDialogComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ HistoricoStatusDialogComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(HistoricoStatusDialogComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
