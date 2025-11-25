import { Component, OnInit } from '@angular/core';
import { Producto, ProductoService } from '../../../../core/services/producto';
import { Router } from '@angular/router';

@Component({
  selector: 'app-producto-list',
  templateUrl: './producto-list.html',
  styleUrls: ['./producto-list.scss'],
  standalone: false
})
export class ProductoList implements OnInit {
  
  productos: Producto[] = [];
  loading: boolean = true;

  constructor(
    private productoService: ProductoService,
    private router: Router
  ) {}

  ngOnInit(): void {
    this.cargarProductos();
  }

  cargarProductos() {
    this.loading = true;
    this.productoService.getAll().subscribe({
      next: (data) => {
        this.productos = data;
        this.loading = false;
      },
      error: (err) => {
        this.loading = false;
      }
    });
  }

  crearProducto() {
    this.router.navigate(['/productos/nuevo']);
  }

  editarProducto(id: number) {
    this.router.navigate(['/productos/editar', id]);
  }
}