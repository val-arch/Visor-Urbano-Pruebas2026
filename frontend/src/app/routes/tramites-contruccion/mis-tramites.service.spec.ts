import { TestBed } from '@angular/core/testing';

import { MisTramitesService } from './mis-tramites.service';

describe('MisTramitesService', () => {
  let service: MisTramitesService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(MisTramitesService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
