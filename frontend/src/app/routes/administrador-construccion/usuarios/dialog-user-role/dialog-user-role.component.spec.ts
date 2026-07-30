import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DialogUserRoleComponent } from './dialog-user-role.component';

describe('DialogUserRoleComponent', () => {
  let component: DialogUserRoleComponent;
  let fixture: ComponentFixture<DialogUserRoleComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DialogUserRoleComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DialogUserRoleComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
