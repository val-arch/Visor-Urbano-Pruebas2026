import { TestBed } from '@angular/core/testing';

import { CamposTramiteServiceService } from './campos-tramite-service.service';

describe('CamposTramiteServiceService', () => {
  let service: CamposTramiteServiceService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(CamposTramiteServiceService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
