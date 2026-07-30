import { TestBed } from '@angular/core/testing';

import { IniciarTramiteService } from './iniciar-tramite.service';

describe('IniciarTramiteService', () => {
  let service: IniciarTramiteService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(IniciarTramiteService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
