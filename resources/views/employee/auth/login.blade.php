<x-employee.auth-layout>

    <!-- Banner -->
    <div class="banner-wrapper shape-1">
        <div class="container inner-wrapper">
            <h2 class="dz-title">Masuk</h2>
            <p class="mb-0">Isi formulir untuk masuk</p>
        </div>
    </div>
    <!-- Banner End -->
    <div class="container">
        <div class="account-area">
            <form action="{{ route('employee.login') }}" method="POST">
                @csrf
                <div class="input-group">
                    <input type="email" required name="email" placeholder="Email" class="form-control">
                </div>
                <div class="input-group">
                    <input type="password" placeholder="Password" name="password" id="dz-password" class="form-control be-0">
                    <span class="input-group-text show-pass">
                        <i class="fa fa-eye-slash"></i>
                        <i class="fa fa-eye"></i>
                    </span>
                </div>
                <a href="#" class="btn-link d-block text-center">Lupa password?</a>

                <footer class="footer fixed">
                    <div class="container">
                        <button type="submit" class="btn mt-2 btn-primary w-100 btn-rounded">Login</button>
                    </div>
                </footer>
            </form>

        </div>
    </div>
    </div>
</x-employee.auth-layout>
