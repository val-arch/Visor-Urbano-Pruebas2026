import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PieAdminComponent } from './pie-admin.component';

describe('PieAdminComponent', () => {
  let component: PieAdminComponent;
  let fixture: ComponentFixture<PieAdminComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PieAdminComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PieAdminComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
