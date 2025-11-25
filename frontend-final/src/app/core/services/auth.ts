import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';
import { Observable, tap } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  private apiUrl = 'http://localhost/proyecto-final/backend-final/public/index.php';
  
  private tokenKey = 'token';
  private userKey = 'user_data';

  constructor(private http: HttpClient, private router: Router) { }

  login(credentials: { email: string; password: string; }): Observable<any> {
    
    const body = {
      usuario_correo: credentials.email,
      usuario_password: credentials.password
    };

    return this.http.post<any>(`${this.apiUrl}/login`, body).pipe(
      tap(response => {
        if (response.response === 'success' && response.data.token) {
        
          localStorage.setItem(this.tokenKey, response.data.token);
          
          localStorage.setItem(this.userKey, JSON.stringify({
            id: response.data.usuario_id,
            nombre: response.data.usuario_nombre
          }));
        }
      })
    );
  }

  logout(): void {
    localStorage.removeItem(this.tokenKey);
    localStorage.removeItem(this.userKey);
    this.router.navigate(['/auth/login']);
  }

  getToken(): string | null {
    return localStorage.getItem(this.tokenKey);
  }

  getCurrentUserId(): number {
    const userStr = localStorage.getItem(this.userKey);
    if (userStr) {
      const user = JSON.parse(userStr);
      return user.id || 1;
    }
    return 1;
  }

  hasToken(): boolean {
    return !!this.getToken();
  }
}