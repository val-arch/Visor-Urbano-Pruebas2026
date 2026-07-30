import { TestBed } from '@angular/core/testing';

import { HistoricoLicenciaService } from './historico-licencia.service';

describe('HistoricoLicenciaService', () => {
  let service: HistoricoLicenciaService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(HistoricoLicenciaService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
