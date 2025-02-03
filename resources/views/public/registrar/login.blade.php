<x-public.layout title="Login">
    <!-- Page Content -->
    <div class="content top-space" style="min-height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #6a11cb, #2575fc);">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <!-- Login Card -->
                    <div class="card shadow-lg border-0 rounded-lg overflow-hidden">
                        <div class="row g-0">
                            <!-- Left Side - Image -->
                            <div class="col-md-6 d-none d-md-block">
                                <img src="{{asset('admin/img/registresel.jpg')}}" class="img-fluid h-100" alt="Login" style="object-fit: cover;">
                            </div>
                            <!-- Right Side - Form -->
                            <div class="col-md-6 p-5 bg-white">
                                <div class="login-header text-center mb-4">
                                    <x-alert/>
                                    <h3 class="fw-bold">Bem-vindo de volta!</h3>
                                    <p class="text-muted">Por favor, faça login para continuar.</p>
                                </div>
                                <form action="{{route('user.login')}}" method="POST">
                                    @csrf
                                    <!-- Email Input -->
                                    <div class="form-group mb-4">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" name="email" id="email" class="form-control form-control-lg" placeholder="Digite seu email">
                                        @error('email')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <!-- Password Input -->
                                    <div class="form-group mb-4">
                                        <label for="senha" class="form-label">Senha</label>
                                        <input type="password" name="senha" id="senha" class="form-control form-control-lg" placeholder="Digite sua senha">
                                        @error('senha')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <!-- Forgot Password Link -->
                                    <div class="d-flex justify-content-end mb-4">
                                        <a href="#" class="text-decoration-none text-primary">Esqueceu a senha?</a>
                                    </div>
                                    <!-- Login Button -->
                                    <button class="btn btn-primary w-100 btn-lg mb-3" type="submit">Login</button>
                                    <!-- Divider -->
                                    <div class="login-or text-center my-4">
                                        <span class="text-muted">Ou</span>
                                    </div>
                                    <!-- Social Login Buttons -->
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <a href="{{route('aluno.googleAuth.redirect')}}" class="btn btn-outline-danger w-100">
                                                <i class="fab fa-google me-2"></i> Google
                                            </a>
                                        </div>
                                        <div class="col-6">
                                            <a href="#" class="btn btn-outline-primary w-100">
                                                <i class="fab fa-facebook-f me-2"></i> Facebook
                                            </a>
                                        </div>
                                    </div>
                                    <!-- Register Link -->
                                    <div class="text-center mt-4">
                                        <p class="text-muted">Não tem uma conta? <a href="{{route('home.registerAluno')}}" class="text-decoration-none text-primary">Registre-se</a></p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- /Login Card -->
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Content -->
</x-public.layout>