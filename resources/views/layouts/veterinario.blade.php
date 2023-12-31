<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LOCILAB - VETERINARIO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/0ab2bcde1c.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" rel="stylesheet" />
    <link href="{{ asset('vet/css/main.min.css') }}" rel="stylesheet">

</head>

<body>
    <div id="spinner-overlay">
        <div class="spinner"></div>
    </div>

    <div>
        @yield('content')
    </div>
    <div class="menu-mobile">
        <div class="menu-mobile-content">
            <div class="menu-mobile-header">
                <div class="menu-mobile-header-logo">
                    <img src="{{ asset('vet/img/logo.svg') }}" alt="">
                </div>
                <div class="menu-mobile-header-close">
                    <button class="btn-menu btn-menu-close"><i class="fas fa-times"></i></button>
                </div>
            </div>
            <div class="menu-mobile-body">
                <div class="menu-mobile-body-item home">
                    <div class="menu-mobile-body-item-icon">
                        <i class="fa-solid fa-home"></i>
                    </div>
                    <div class="menu-mobile-body-item-text">
                        <p>Home</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script src="http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.6.2/modernizr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.7/dist/iconify-icon.min.js"></script>
    <script src="{{ asset('adm/assets/js/fabric.min.js') }}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="{{ asset('adm/assets/js/main.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.js-example-basic-single').select2({
                placeholder: "Selecione o proprietário",
                allowClear: true,
                width: '100%',
            });
            $('.btn-menu').click(function() {
                $('.menu-mobile').toggleClass('menu-mobile-active');

            });
            $('.home').click(function() {
                window.location.href = "{{ route('vet.index') }}";
            });
            $('.user').click(function() {
                $('.user-configs').toggleClass('user-configs-active');
            });
        });

        /// Spinner
        window.addEventListener('load', function() {
            var spinnerOverlay = document.getElementById('spinner-overlay');
            spinnerOverlay.style.display = 'none';
        });
    </script>
    @yield('js')
    @if (Session::has('success'))
        <script type="text/javascript">
            Swal.fire({
                title: 'Sucesso!',
                icon: 'success',
                text: "{{ Session::get('success') }}",
                timer: 5000,
                type: 'success'
            }).then((result) => {
                // Reload the Page
                location.reload();
            });
        </script>
    @endif
    @if (Session::has('error'))
        <script type="text/javascript">
            Swal.fire({
                title: 'Oops!',
                icon: 'error',
                text: "{{ Session::get('error') }}",
                timer: 5000,
                type: 'error'
            }).then((result) => {
                // Reload the Page
                location.reload();
            });
        </script>
    @endif
</body>

</html>
