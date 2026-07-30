import { Component, OnInit } from '@angular/core';
import { FuseSidebarService } from '../../../../@fuse/components/sidebar/sidebar.service';
import { FuseConfigService } from '../../../../@fuse/services/config.service';

@Component({
  selector: 'app-dialog-avisos-privacidad',
  templateUrl: './dialog-avisos-privacidad.component.html',
  styleUrls: ['./dialog-avisos-privacidad.component.scss']
})
export class DialogAvisosPrivacidadComponent implements OnInit {

  constructor(    private _fuseConfigService: FuseConfigService) { 
    
  }

  ngOnInit(): void {
    this._fuseConfigService.config = {
      layout: {
          navbar: {
              hidden: true,
          },
          toolbar: {
              hidden: true,
          },
          footer: {
              hidden: true,
          },
          sidepanel: {
              hidden: true,
          },
      },
  };
  }

}
