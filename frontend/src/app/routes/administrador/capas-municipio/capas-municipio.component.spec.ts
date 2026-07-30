import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CapasMunicipioComponent } from './capas-municipio.component';

describe('CapasMunicipioComponent', () => {
  let component: CapasMunicipioComponent;
  let fixture: ComponentFixture<CapasMunicipioComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CapasMunicipioComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CapasMunicipioComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
