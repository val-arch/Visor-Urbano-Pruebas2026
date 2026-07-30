import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { NotificacionesDialogComponent } from './notificaciones-dialog.component';

describe('NotificacionesDialogComponent', () => {
  let component: NotificacionesDialogComponent;
  let fixture: ComponentFixture<NotificacionesDialogComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ NotificacionesDialogComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(NotificacionesDialogComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
