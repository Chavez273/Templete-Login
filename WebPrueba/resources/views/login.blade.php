<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inicio de sesión</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/icheck-bootstrap/3.0.1/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="#"><b>TestStart</b></a>
    </div>

    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Inicie Sesión</p>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="post">
                @csrf
                <div class="input-group mb-3">
                    <!-- ELIMINADO: value="{{ old('email') }}" -->
                    <input type="text" class="form-control" placeholder="Email/Usuario" name="email" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" placeholder="Contraseña" name="password" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember">Recordar sesión</label>
                        </div>
                    </div>
                    <div class="col-12 mt-2">
                        <button type="submit" class="btn btn-primary btn-block">Iniciar Sesión</button>
                    </div>
                </div>
            </form>

            <div class="social-auth-links text-center mb-3 mt-3">
                <p>- O -</p>
                <a href="#" class="btn btn-block btn-primary mb-2">
                    <i class="fab fa-facebook mr-2"></i> Iniciar con Facebook
                </a>
                <a href="#" class="btn btn-block btn-danger">
                    <i class="fab fa-google mr-2"></i> Iniciar con Gmail
                </a>
            </div>

            <p class="mb-1">
                <a href="{{ route('password.request') }}">Olvidé mi contraseña</a>
            </p>
            <p class="mb-0">
                <a href="{{ route('register') }}" class="text-center">Registrarse</a>
            </p>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"></script>

<script>
// JavaScript para limpiar el campo de email/username cuando se muestre un error
document.addEventListener('DOMContentLoaded', function() {
    // Si hay errores en la página, limpiar el campo de email/username
    @if($errors->any())
        document.querySelector('input[name="email"]').value = '';
    @endif
});
</script>
</body>
</html>
