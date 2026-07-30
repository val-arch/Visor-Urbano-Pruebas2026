import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ResumenTramiteConstruccionComponent } from './resumen-tramite-construccion.component';

describe('ResumenTramiteConstruccionComponent', () => {
  let component: ResumenTramiteConstruccionComponent;
  let fixture: ComponentFixture<ResumenTramiteConstruccionComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ResumenTramiteConstruccionComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ResumenTramiteConstruccionComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
