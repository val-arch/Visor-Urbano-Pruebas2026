import { TestBed } from '@angular/core/testing';

import { CamposTramiteConstruccionService } from './campos-tramite-construccion.service';

describe('CamposTramiteConstruccionService', () => {
  let service: CamposTramiteConstruccionService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(CamposTramiteConstruccionService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
