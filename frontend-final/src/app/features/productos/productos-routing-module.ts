import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { ProductoList } from './pages/producto-list/producto-list';
import { ProductoForm } from './pages/producto-form/producto-form';

const routes: Routes = [
    // Ruta por defecto: /productos/ -> Muestra la Lista
  { path: '', component: ProductoList },
  
  // Ruta para crear: /productos/nuevo -> Muestra el Formulario
  { path: 'nuevo', component: ProductoForm },
  
  // Ruta para editar: /productos/editar/5 -> Muestra el Formulario con datos
  { path: 'editar/:id', component: ProductoForm }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class ProductosRoutingModule { }
