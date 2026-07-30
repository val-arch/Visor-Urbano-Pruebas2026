import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ResumenTramiteRevisionComponent } from './resumen-tramite-revision.component';

describe('ResumenTramiteComponent', () => {
  let component: ResumenTramiteRevisionComponent;
  let fixture: ComponentFixture<ResumenTramiteRevisionComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ResumenTramiteRevisionComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ResumenTramiteRevisionComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
