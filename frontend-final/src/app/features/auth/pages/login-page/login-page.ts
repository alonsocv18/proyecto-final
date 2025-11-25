import { Component } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { AuthService } from '../../../../core/services/auth';

@Component({
  selector: 'app-login-page',
  templateUrl: './login-page.html',
  styleUrls: ['./login-page.scss'],
  standalone: false
})
export class LoginPage {
  loginForm: FormGroup;
  loading: boolean = false;
  errorMessage: string = '';

  constructor(
    private fb: FormBuilder,
    private authService: AuthService,
    private router: Router
  ) {
    this.loginForm = this.fb.group({
      email: ['', [Validators.required, Validators.email]],
      password: ['', [Validators.required, Validators.minLength(3)]] 
    });
  }

  get f() { return this.loginForm.controls; }

  // Helper para verificar validez visualmente
  isFieldInvalid(field: string): boolean {
    const control = this.loginForm.get(field);
    return !!(control && control.invalid && (control.dirty || control.touched));
  }

  onSubmit() {
    // 1. Si el formulario es inválido, marcamos todo como tocado para mostrar errores rojos
    if (this.loginForm.invalid) {
      this.loginForm.markAllAsTouched();
      return;
    }

    this.loading = true;
    this.errorMessage = '';

    // 2. Extraemos credenciales
    const credentials = {
      email: this.loginForm.get('email')?.value,
      password: this.loginForm.get('password')?.value
    };

    // 3. Llamamos al servicio (Aquí se usa el Observable)
    this.authService.login(credentials).subscribe({
      next: (resp) => {
        // ÉXITO: El token ya se guardó en el servicio, redirigimos
        this.loading = false;
        this.router.navigate(['/productos']); 
      },
      error: (err) => {
        this.loading = false;
        
        if (err.status === 401 || err.status === 403) {
          this.errorMessage = 'Correo o contraseña incorrectos.';
        } else {
          this.errorMessage = 'Ocurrió un error en el servidor. Intenta más tarde.';
        }
      }
    });
  }
}