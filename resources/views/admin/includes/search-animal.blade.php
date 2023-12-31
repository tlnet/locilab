<div class="p-4">
    @forelse ($animals as $animal)
        @if ($animal->status == 1)
            @php
                $status = 'Aguardando amostra';
            @endphp
        @elseif($animal->status == 2)
            @php
                $status = 'Amostra recebida';
            @endphp
        @elseif($animal->status == 3)
            @php
                $status = 'Em análise';
            @endphp
        @elseif($animal->status == 4)
            @php
                $status = 'Análise concluída';
            @endphp
        @elseif($animal->status == 5)
            @php
                $status = 'Resultado disponível';
            @endphp
        @elseif($animal->status == 6)
            @php
                $status = 'Análise reprovada';
            @endphp
        @elseif($animal->status == 7)
            @php
                $status = 'Análise Aprovada';
            @endphp
        @elseif($animal->status == 8)
            @php
                $status = 'Recoleta solicitada';
            @endphp
        @elseif($animal->status == 9)
            @php
                $status = 'Pagamento confirmado';
            @endphp
        @elseif($animal->status == 10)
            @php
                $status = 'Pedido concluído';
            @endphp
        @elseif($animal->status == 11)
            @php
                $status = 'Aguardando pagamento';
            @endphp
        @endif
        <div class="filter-changed">
            <div class="ajust">
                <div class="row">
                    <div class="col-3">
                        <p>Produto</p>
                    </div>
                    <div class="col-4">
                        <p>Criador</p>
                    </div>
                    <div class="col-2">
                        <p>Status</p>
                    </div>
                    <div class="col-3">
                        <p>Ações</p>
                    </div>

                </div>
                <div class="row">
                    <div class="col-3">
                        <p>{{ $animal->animal_name }}</p>
                    </div>
                    <div class="col-4">
                        <p>{{ $animal->order->creator ?? 'Sem criador' }}</p>
                    </div>
                    <div class="col-2">
                        <p>{{ $status }}</p>
                    </div>

                    <div class="col-3">
                        <div class="dropdown">
                            <a class="btn btn-alt-loci text-white dropdown-toggle" href="#" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Ações
                            </a>

                            <ul class="dropdown-menu">
                                <a href="@if ($animal->order->origin == 'email') {{ route('order.detail', $animal->order_id) }} @else {{ route('order.sistema.detail', $animal->order_id) }} @endif" class="dropdown-item" id="show-btn">Ver</a>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @empty
        <h4>Sem itens</h4>
    @endforelse
</div>
