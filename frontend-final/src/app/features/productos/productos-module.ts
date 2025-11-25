import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { ProductosRoutingModule } from './productos-routing-module';
import { ProductoList } from './pages/producto-list/producto-list';
import { ProductoForm } from './pages/producto-form/producto-form';
import { SharedModule } from '../../shared/shared-module';

@NgModule({
  declarations: [
    ProductoList,
    ProductoForm
  ],
  imports: [
    CommonModule,
    ProductosRoutingModule,
    SharedModule
  ]
})
export class ProductosModule { }
