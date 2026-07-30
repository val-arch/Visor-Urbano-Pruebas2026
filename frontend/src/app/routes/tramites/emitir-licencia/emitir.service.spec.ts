import { TestBed } from '@angular/core/testing';

import { EmitirService } from './emitir.service';

describe('EmitirService', () => {
  let service: EmitirService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(EmitirService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
