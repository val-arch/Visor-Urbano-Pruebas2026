import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SheetDibujarComponent } from './sheet-dibujar.component';

describe('SheetDibujarComponent', () => {
  let component: SheetDibujarComponent;
  let fixture: ComponentFixture<SheetDibujarComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SheetDibujarComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SheetDibujarComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
