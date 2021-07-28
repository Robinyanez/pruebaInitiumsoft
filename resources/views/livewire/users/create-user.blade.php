<div class="card-body">
    <div class="row">
        <div class="col-sm-12 col-md-4">
            <div class="form-group row">
                <div class="col-sm-2">
                    <label for="">Id:</label>
                </div>
                <div>
                    <input type="text" class="form-control @error('codigo_turno') is-invalid @enderror" id="codigo_turno" wire:model="codigo_turno" readonly placeholder="Id...">
                        @error('codigo_turno')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-5">
            <div class="form-group row">
                <div class="col-sm-3">
                    <label for="">Nombres:</label>
                </div>
                <div>
                    <input style="width: 300px" type="text" class="form-control @error('name') is-invalid @enderror" id="name" wire:model="name" placeholder="Nombres...">
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-2" style="text-align:center">
            <button wire:click.prevent="store()" type="button" class="btn btn-primary">Guardar</button>
        </div>
    </div>
    <hr>
    <br>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group row">
                <div class="col-sm-1">
                    <label for="staticEmail">Cola 1:</label>
                </div>
                <div class="col-sm-4">
                    <input style="width: 300px" type="text" class="form-control" id="cola_1" readonly wire:model="name_cola1">
                </div>
                <div wire:ignore>Siguiente turno en <span id="time1">02:00</span> minutos!</div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group row">
                <div class="col-sm-1">
                    <label for="">Cola 2:</label>
                </div>
                <div class="col-sm-4">
                    <input style="width: 300px" type="text" class="form-control" id="cola_2" readonly wire:model="name_cola2">
                </div>
                <div wire:ignore>Siguiente turno en <span id="time2">03:00</span> minutos!</div>
            </div>
        </div>
    </div>
    <hr>
    <br>
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Por Atender</h4>
                </div>
                <div class="card-body">
                    @if ($turnoPendienteQuery->count())
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Codigo</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($turno_pendiente as $item)
                                <tr>
                                    <td>{{ $item->codigo }}</td>
                                    <td>{{ $item->name }}</td>
                                    @if ($item->estado == 1)
                                        <td><p style="color: red">Pendiente</p></td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="container text-center d-flex justify-content-center align-items-center m-3">
                            {{ $turno_pendiente->links() }}
                        </div>
                    @else
                        <p>No existen turnos por atender</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Atendidos</h4>
                </div>
                <div class="card-body">
                    @if ($turnoAtendidoQuery->count())
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Codigo</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Atención</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($turno_atendido as $value)
                                <tr>
                                    <td>{{ $value->codigo }}</td>
                                    <td>{{ $value->name }}</td>
                                    @if ($value->estado == 3)
                                        <td><p style="color: green">Atendido</p></td>
                                    @endif
                                    @if ($value->cola == 1)
                                        <td>Cola 1</td>
                                    @else
                                        <td>Cola 2</td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="container text-center d-flex justify-content-center align-items-center m-3">
                            {{ $turno_atendido->links() }}
                        </div>
                    @else
                        <p>No existen turnos atendidos</p>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
