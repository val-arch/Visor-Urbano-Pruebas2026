import { TestBed } from '@angular/core/testing';

import { LicenciaStatusService } from './licencia-status.service';

describe('LicenciaStatusService', () => {
  let service: LicenciaStatusService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(LicenciaStatusService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
