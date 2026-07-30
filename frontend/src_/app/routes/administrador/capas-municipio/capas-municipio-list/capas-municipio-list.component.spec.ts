import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CapasMunicipioListComponent } from './capas-municipio-list.component';

describe('CapasMunicipioListComponent', () => {
  let component: CapasMunicipioListComponent;
  let fixture: ComponentFixture<CapasMunicipioListComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CapasMunicipioListComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CapasMunicipioListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
