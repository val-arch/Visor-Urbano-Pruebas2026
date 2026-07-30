import { Component, OnInit, Input, Output, EventEmitter } from '@angular/core';

@Component({
  selector: 'app-map-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.scss']
})
export class HeaderComponent implements OnInit {
  @Input('activeMedia') activeMedia: any;
  @Output() barra = new EventEmitter<boolean>();
  constructor() { }

  ngOnInit(): void {
  }

  addActive(){
    this.barra.emit(true);
  }


}
