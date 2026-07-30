import { Injectable } from '@angular/core';
import { BehaviorSubject, Observable } from 'rxjs';
import { LocalStorageService } from '../shared/services/storage.service';

export const USER_KEY = 'usr';

export interface User {
  id: number;
  name?: string;
  email?: string;
  avatar?: string;
  rol?: number,
  rol_name?:string
  id_municipio?:number
  nombre_municipio?:string
  image_municipio?:string
}

@Injectable({
  providedIn: 'root',
})
export class SettingsService {
  constructor(private store: LocalStorageService) {}


  /** User information */

  get user() {
    return this.store.get(USER_KEY);
  }

  setUser(value: User) {
    this.store.set(USER_KEY, value);
  }

  removeUser() {
    this.store.remove(USER_KEY);
  }


}
