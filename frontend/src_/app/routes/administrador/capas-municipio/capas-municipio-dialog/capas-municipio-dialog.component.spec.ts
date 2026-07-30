import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CapasMunicipioDialogComponent } from './capas-municipio-dialog.component';

describe('CapasMunicipioDialogComponent', () => {
  let component: CapasMunicipioDialogComponent;
  let fixture: ComponentFixture<CapasMunicipioDialogComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CapasMunicipioDialogComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CapasMunicipioDialogComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
