<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Ventas</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5.3.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6.5.2 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <!-- Estilos propios -->
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>

<body class="d-flex align-items-center justify-content-center vh-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5 col-xl-4">
                <div class="card shadow-lg border-0 rounded-4 animate-fadeInUp" style="background: rgba(255,255,255,0.95); backdrop-filter: blur(10px);">
                    <div class="card-body p-5">
                        <!-- Header -->
                        <div class="text-center mb-4">
                            <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                 style="width: 80px; height: 80px;">
                                <i class="fa-solid fa-cash-register fa-2x text-white"></i>
                            </div>
                            <h2 class="fw-bold text-gradient mb-2">Sistema de Ventas</h2>
                            <p class="text-muted">Inicia sesión para acceder al dashboard</p>
                        </div>

                        <!-- Formulario -->
                        <form id="loginForm" class="needs-validation" novalidate>
                            <div class="mb-4">
                                <label for="correo" class="form-label fw-semibold">
                                    <i class="fa-solid fa-envelope me-2 text-primary"></i>Correo Electrónico
                                </label>
                                <input type="email" 
                                       class="form-control form-control-lg" 
                                       id="correo" 
                                       name="correo" 
                                       placeholder="tu@email.com" 
                                       required>
                                <div class="invalid-feedback">
                                    Por favor ingresa un correo válido.
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="clave" class="form-label fw-semibold">
                                    <i class="fa-solid fa-lock me-2 text-primary"></i>Contraseña
                                </label>
                                <div class="input-group input-group-lg">
                                    <input type="password" 
                                           class="form-control" 
                                           id="clave" 
                                           name="clave" 
                                           placeholder="••••••••" 
                                           required>
                                    <button class="btn btn-outline-secondary" 
                                            type="button" 
                                            id="togglePassword"
                                            data-bs-toggle="tooltip" 
                                            title="Mostrar/Ocultar contraseña">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback">
                                    Por favor ingresa tu contraseña.
                                </div>
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fa-solid fa-sign-in-alt me-2"></i>
                                    Iniciar Sesión
                                </button>
                            </div>
                        </form>

                        <!-- Mensaje de estado -->
                        <div id="mensaje" class="text-center"></div>

                        <!-- Footer -->
                        <div class="text-center mt-4">
                            <small class="text-muted">
                                <i class="fa-solid fa-shield-halved me-1"></i>
                                Acceso seguro y encriptado
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script de login -->
    <script src="../../assets/js/login.js"></script>
    
    <!-- Script adicional para validaciones -->
    <script>
        // Validación de formulario
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();

        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordField = document.getElementById('clave');
            const icon = this.querySelector('i');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
                this.classList.add('active');
            } else {
                passwordField.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
                this.classList.remove('active');
            }
        });

        // Inicializar tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
</body>
</html>