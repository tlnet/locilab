@extends('layouts.admin')
@section('content')
    <div class="container">
        <div class="card my-4">
            <div class="card-header">
                <h4>Criar ou adicionar produto</h4>
            </div>
            <div class="card-body">
                <div class="mb-5">
                    <div>
                        <h5>Inserir animal existente</h5>
                    </div>
                    <form action="{{ route('admin.order-update-animal') }}" method="post">
                        @csrf
                        <input type="hidden" value="{{ $order->id }}" name="order">
                        <div class="row">
                            <div class="col-md-8">
                                <label for="">Buscar animal </label>
                                <input type="search" class="form-control" id="search-input"
                                    placeholder="Digite o nome do animal..">
                                <div class="my-1 d-none" id="div-results">
                                    <select name="animal" class="form-control" id="resuts">

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary">Adicionar</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="mt-4">
                    <h5>Cadastrar novo animal</h5>
                </div>
                <form action="{{ route('admin.order-add-animal-post') }}" method="post">
                    @csrf
                    <input type="hidden" name="order" value="{{ $order->id }}">
                    <div class="row">
                        <input type="hidden" name="id">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Nome do animal</label>
                                    <input type="text" name="animal_name" id="animal_name" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Registro do animal</label>
                                    <input type="text" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">ID do animal</label>
                                    <input type="text" name="register_number_brand" id="register_number_brand"
                                        class="form-control">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">CHIP do animal</label>
                                    <input type="text" name="chip_number" id="register_number_brand"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Sexo</label>
                                    <select class="form-select" name="sex">
                                        <option value="F">F</option>
                                        <option value="M">M</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Espécie</label>
                                    <select class="form-select species" name="especies">
                                        <option>Selecione e espécie</option>
                                        @foreach ($species as $specie)
                                            <option value="{{ $specie->name }}" data-specie="{{ $specie->id }}">
                                                {{ $specie->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Raça</label>
                                    <select class="form-select breeds" name="breed">
                                        <option>Selecione a raça</option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Data de coleta</label>
                                    <input type="date" name="" id="" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Data de nascimento</label>
                                    <input type="date" name="birth_date" id="birth_date" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Idade</label>
                                    <input type="text" name="age" id="age" class="form-control">
                                </div>
                            </div>


                            <div class="col-md-6 pai">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Numero de registro do
                                        pai</label>
                                    <input type="text" name="registro_pai" id="registro_pai"
                                        class="form-control registro_pai">
                                </div>
                            </div>
                            <div class="col-md-6 pai">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Espécie do pai</label>
                                    <select class="form-select" name="especie_pai">
                                        <option>Selecione a espécie</option>
                                        @foreach ($species as $specie)
                                            <option value="{{ $specie->name }}">{{ $specie->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 pai">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Nome do pai</label>
                                    <input type="text" name="pai" id="pai" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6 mae">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Numero de registro da
                                        mãe</label>
                                    <input type="text" name="registro_mae" id="registro_mae"
                                        class="form-control registro_mae">
                                </div>
                            </div>
                            <div class="col-md-6 mae">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Espécie da mãe</label>
                                    <select class="form-select" name="especie_mae">
                                        <option>Selecione a espécie</option>
                                        @foreach ($species as $specie)
                                            <option value="{{ $specie->name }}">{{ $specie->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mae">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Nome da mãe</label>
                                    <input type="text" name="mae" id="mae" class="form-control">
                                </div>
                            </div>


                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Salvar</button>
                            </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {

            $(document).ready(function() {
                $('#search-input').on('keyup', function() {
                    var query = $(this).val();
                    console.log(query.length)

                    $.ajax({
                        url: '{{ route('get.registros.animais') }}',
                        type: 'GET',
                        data: {
                            query: query
                        },
                        dataType: 'json',
                        success: function(data) {
                            // Faça algo com os resultados da busca
                            $('#resuts').empty();
                            $.each(data, function(index, item) {

                                $('#div-results').removeClass('d-none');
                                $('#resuts').append(
                                    `<option value="${item.id}">${item.animal_name}</option>`
                                )
                            });
                            if (query.length <= 1) {
                                $('#div-results').addClass('d-none');
                            }
                        }
                    });
                });
            });

            $('#birth_date').change(function() {
                var birth_date = $('#birth_date').val();
                var date = new Date(birth_date);
                var today = new Date();
                var age = Math.floor((today - date) / (365.25 * 24 * 60 * 60 * 1000));
                $('#age').val(age);
            });

            $('.species').change(function() {
                var specie = $(this).find(':selected').data('specie');
                $.ajax({
                    url: '/get-breeds/' + specie,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        console.log(response)
                        var len = 0;
                        if (response != null) {
                            len = response.length;
                        }
                        if (len > 0) {
                            $(".breeds").empty();
                            for (var i = 0; i < len; i++) {
                                var id = response[i].id;
                                var name = response[i].name;
                                var option = "<option value='" + name + "'>" + name +
                                    "</option>";
                                $(".breeds").append(option);
                            }
                        }
                    }
                });
            });
            $('.extra-verification').change(function() {
                if ($(this).is(':checked')) {
                    $('.extra-verification').val('1');
                    $('.type-verify').removeClass('d-none');

                } else {
                    $('.extra-verification').val('0');
                    $('.type-verify').addClass('d-none');
                }
            });
            $('.type-verify').change(function() {
                var type = $(this).find(':selected').data('verify');
                if (type == 1) {
                    $('.pai').addClass('d-none');
                    $('.mae').addClass('d-none');
                }
                if (type == 2) {
                    $('.mae').removeClass('d-none');
                    $('.pai').addClass('d-none');
                }
                if (type == 3) {
                    $('.mae').addClass('d-none');
                    $('.pai').removeClass('d-none');
                }
                if (type == 4) {
                    $('.pai').removeClass('d-none');
                    $('.mae').removeClass('d-none');
                }

            });



        });
    </script>
@endsection
