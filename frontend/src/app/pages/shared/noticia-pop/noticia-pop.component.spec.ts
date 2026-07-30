import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { NoticiaPopComponent } from './noticia-pop.component';

describe('NoticiaPopComponent', () => {
  let component: NoticiaPopComponent;
  let fixture: ComponentFixture<NoticiaPopComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ NoticiaPopComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(NoticiaPopComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
