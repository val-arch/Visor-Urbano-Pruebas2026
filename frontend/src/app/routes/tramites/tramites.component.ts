import { Component, OnInit } from '@angular/core';
import { fuseAnimations } from '@fuse/animations/index';

@Component({
  selector: 'app-tramites',
  templateUrl: './tramites.component.html',
  styleUrls: ['./tramites.component.scss'],
  animations: fuseAnimations
})
export class TramitesComponent implements OnInit {

  constructor() { }

  ngOnInit(): void {
  }

}
