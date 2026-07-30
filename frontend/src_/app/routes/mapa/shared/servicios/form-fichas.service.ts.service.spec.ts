import { TestBed } from '@angular/core/testing';

import { FormFichas.Service.TsService } from './form-fichas.service.ts.service';

describe('FormFichas.Service.TsService', () => {
  let service: FormFichas.Service.TsService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(FormFichas.Service.TsService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
