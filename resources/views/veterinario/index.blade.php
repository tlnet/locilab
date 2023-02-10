@extends('layouts.veterinario')
@section('content')
    <div class="main">
        <div class="content row justify-content-around">
            <div class="col-4 menu-content" id="resenha">
                <div class="mt-3">
                    <div>
                        <img src="{{ asset('vet/img/resenha.png') }}" alt="">
                    </div>
                    <div>
                        <p>Resenha</p>
                    </div>
                </div>

            </div>
            <div class="col-4 menu-content">
                <div class="mt-3">
                    <div>
                        <img src="{{ asset('vet/img/animais.png') }}" alt="">
                    </div>
                    <div>
                        <p>Animais</p>
                    </div>
                </div>

            </div>

        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $(document).on('click', '#resenha', function() {
                window.location.href = "{{ route('resenha.step1') }}";
            });
        });
    </script>
@endsection