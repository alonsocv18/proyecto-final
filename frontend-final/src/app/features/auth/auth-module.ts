import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule, FormsModule } from '@angular/forms'; // <--- OBLIGATORIO

import { AuthRoutingModule } from './auth-routing-module';
import { LoginPage } from './pages/login-page/login-page';

@NgModule({
  declarations: [
    LoginPage
  ],
  imports: [
    CommonModule,
    AuthRoutingModule,
    ReactiveFormsModule,
    FormsModule
  ]
})
export class AuthModule { }