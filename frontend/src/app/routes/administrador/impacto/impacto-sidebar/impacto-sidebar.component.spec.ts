import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ImpactoSidebarComponent } from './impacto-sidebar.component';

describe('SidebarComponent', () => {
  let component: ImpactoSidebarComponent;
  let fixture: ComponentFixture<ImpactoSidebarComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ImpactoSidebarComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ImpactoSidebarComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
