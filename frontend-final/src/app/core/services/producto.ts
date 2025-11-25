import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, map } from 'rxjs';

export interface Producto {
  producto_id?: number;
  categoria_id: number;
  producto_codigo: string;
  producto_nombre: string;
  producto_descripcion?: string;
  producto_precio: number;
  producto_stock: number;
  categoria_nombre?: string; 
}

@Injectable({
  providedIn: 'root'
})
export class ProductoService {
  private apiUrl = 'http://localhost/proyecto-final/backend-final/public/index.php/api/productos';

  constructor(private http: HttpClient) { }

  getAll(): Observable<Producto[]> {
    return this.http.get<any>(this.apiUrl).pipe(
      map(response => response.data)
    );
  }

  getById(id: number): Observable<Producto> {
    return this.http.get<any>(`${this.apiUrl}/${id}`).pipe(
      map(response => response.data)
    );
  }

  create(producto: Producto): Observable<any> {
    return this.http.post(this.apiUrl, producto);
  }

  update(id: number, producto: Producto): Observable<any> {
    return this.http.put(`${this.apiUrl}/${id}`, producto);
  }
}