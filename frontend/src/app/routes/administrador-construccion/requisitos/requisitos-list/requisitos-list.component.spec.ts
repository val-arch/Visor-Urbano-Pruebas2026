import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { RequisitosListComponent } from './requisitos-list.component';

describe('RequisitosListComponent', () => {
  let component: RequisitosListComponent;
  let fixture: ComponentFixture<RequisitosListComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ RequisitosListComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(RequisitosListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
