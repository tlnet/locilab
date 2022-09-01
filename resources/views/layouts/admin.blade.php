<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>ADMIN - LOCI</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('adm/assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="{{ asset('adm/assets/fonts/fontawesome-all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adm/assets/fonts/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adm/assets/fonts/fontawesome5-overrides.min.css') }}">
</head>

<body id="page-top">
    <div id="wrapper">
        <nav class="navbar navbar-light shadow-lg align-items-start sidebar sidebar-dark accordion bg-gradient-primary p-0"
            style="background: var(--bs-light);color: var(--bs-dark);">
            <div class="container-fluid d-flex flex-column p-0"><img class="img-fluid"
                    src="{{ asset('adm/assets/img/logo.svg') }}" width="100%" loading="lazy"
                    style="width: 150px;margin: 10px;padding: 10px;">
                <hr class="sidebar-divider my-0">
                <ul class="navbar-nav text-light" id="accordionSidebar">
                    <li class="nav-item"><a class="nav-link active" href="{{ route('admin') }}"
                            style="background: var(--bs-gray-300);color: var(--bs-dark);"><i
                                class="fas fa-tachometer-alt" style="color: var(--bs-dark);"></i><span>Home</span></a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="#" style="color: var(--bs-dark);"><i
                                class="fas fa-table" style="color: var(--bs-dark);"></i><span>Pedidos</span></a><a
                            class="nav-link" href="#"
                            style="color: var(--bs-body-color);background: var(--bs-gray-300);"><i class="fas fa-user"
                                style="color: var(--bs-dark);"></i><span>Usuários</span></a></li>
                    <li class="nav-item"></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('exames') }}"
                            style="color: var(--bs-dark);"><i class="far fa-list-alt"
                                style="color: var(--bs-dark);"></i><span>Exames</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="#"
                            style="background: var(--bs-gray-300);color: var(--bs-dark);"><i class="fa fa-gear"
                                style="color: var(--bs-dark);"></i><span>Configurações</span></a></li>
                </ul>
                <div class="text-center d-none d-md-inline"><button class="btn rounded-circle border-0"
                        id="sidebarToggle" type="button"></button></div>
            </div>
        </nav>
        <div class="d-flex flex-column" id="content-wrapper">
            <div id="content" style="margin-top: 0;">
                <nav class="navbar navbar-light navbar-expand bg-white shadow mb-4 topbar static-top">
                    <div class="container-fluid"><button class="btn btn-link d-md-none rounded-circle me-3"
                            id="sidebarToggleTop-1" type="button"><i class="fas fa-bars"></i></button>
                        <form class="d-none d-sm-inline-block me-auto ms-md-3 my-2 my-md-0 mw-100 navbar-search">
                            <div class="input-group"></div>
                        </form>
                        <ul class="navbar-nav flex-nowrap ms-auto">
                            <li class="nav-item dropdown d-sm-none no-arrow"><a class="dropdown-toggle nav-link"
                                    aria-expanded="false" data-bs-toggle="dropdown" href="#"><i
                                        class="fas fa-search"></i></a>
                                <div class="dropdown-menu dropdown-menu-end p-3 animated--grow-in"
                                    aria-labelledby="searchDropdown">
                                    <form class="me-auto navbar-search w-100">
                                        <div class="input-group"><input class="bg-light form-control border-0 small"
                                                type="text" placeholder="Search for ...">
                                            <div class="input-group-append"><button class="btn btn-primary py-0"
                                                    type="button"><i class="fas fa-search"></i></button></div>
                                        </div>
                                    </form>
                                </div>
                            </li>
                            <li class="nav-item dropdown no-arrow mx-1">
                                <div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link"
                                        aria-expanded="false" data-bs-toggle="dropdown" href="#"><span
                                            class="badge bg-danger badge-counter">0+</span><i
                                            class="fas fa-bell fa-fw"></i></a>
                                    <div class="dropdown-menu dropdown-menu-end dropdown-list animated--grow-in">
                                        <h6 class="dropdown-header">Notificaões</h6>
                                        <a class="dropdown-item text-center small text-gray-500" href="#">Todas
                                            a notificaões</a>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item dropdown no-arrow mx-1">
                                <div class="shadow dropdown-list dropdown-menu dropdown-menu-end"
                                    aria-labelledby="alertsDropdown"></div>
                            </li>
                            <div class="d-none d-sm-block topbar-divider"></div>
                            <li class="nav-item dropdown no-arrow">
                                <div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link"
                                        aria-expanded="false" data-bs-toggle="dropdown" href="#"><span
                                            class="d-none d-lg-inline me-2 text-gray-600 small">{{ auth()->user()->name }}</span><img
                                            class="border rounded-circle img-profile"
                                            src="{{ asset('adm/assets/img/avatars/avatar5.jpeg') }}"></a>
                                    <div class="dropdown-menu shadow dropdown-menu-end animated--grow-in"><a
                                            class="dropdown-item" href="#"><i
                                                class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Perfil</a><a
                                            class="dropdown-item" href="#"><i
                                                class="fas fa-cogs fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Configurações</a>
                                        <div class="dropdown-divider"></div><a class="dropdown-item"
                                            href="#"><i
                                                class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Sair</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
                <div>
                    @yield('content')
                </div>
            </div>
            <footer class="bg-white sticky-footer">
                <div class="container my-auto">
                    <div class="text-center my-auto copyright"><span>Copyright © LOCI 2022</span></div>
                </div>
            </footer>
        </div><a class="border rounded d-inline scroll-to-top" href="#page-top"
            style="background: var(--bs-teal);"><i class="fas fa-angle-up"></i></a>
    </div>
    <script src="{{ asset('adm/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('adm/assets/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
    <script src="{{ asset('adm/assets/js/script.min.js') }}"></script>
    <script src="{{ asset('adm/assets/js/main.js') }}"></script>

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
</body>

</html>