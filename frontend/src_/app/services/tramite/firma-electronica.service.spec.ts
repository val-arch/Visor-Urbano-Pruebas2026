import { TestBed } from '@angular/core/testing';

import { FirmaElectronicaService } from './firma-electronica.service';

describe('FirmaElectronicaService', () => {
  let service: FirmaElectronicaService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(FirmaElectronicaService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
