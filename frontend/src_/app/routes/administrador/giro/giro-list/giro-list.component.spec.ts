import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { GiroListComponent } from './giro-list.component';

describe('GiroListComponent', () => {
  let component: GiroListComponent;
  let fixture: ComponentFixture<GiroListComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ GiroListComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(GiroListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
