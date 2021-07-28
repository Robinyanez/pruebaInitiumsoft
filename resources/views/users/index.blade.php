@extends('layouts.app')

@push('css')

    @livewireStyles

@endpush

@section('content')

    <div class="container">
        {{-- <div class="jumbotron"> --}}
            <div class="card">
                <div class="card-header">
                    <h5>Ticket de atención al cliente</h5>
                    <button type="button" id="fin" onclick="changeTurnos(2);" disabled class="btn btn-danger float-sm-right ml-2">
                        Finalizar Atención
                    </button>
                    <button type="button" id="ini" onclick="changeTurnos(1);" class="btn btn-success float-sm-right">
                        Iniciar Atención
                    </button>
                </div>
                <div class="card-body">

                    @livewire('users.create-user')

                </div>
            </div>
        {{-- </div> --}}
    </div>

@endsection

@push('js')

    @livewireScripts

    <script>
        var btnIniciar = document.getElementById('ini');
        var validations1 = 2;
        var validations2 = 2;

        /* Variable para la cola 1*/
        var timer1 ;
        var minutes;
        var seconds;
        var duraciong;


        /* Variable para la cola 2*/
        var timer2;
        var durationg2;
        var minutes2
        var seconds2;

        window.onload = mainPage();

        function mainPage(){
            setInterval(function(){
                if(validations1 == 1 && validations2 == 1){
                    detenerTime();
                }
            }, 1000)
        }

        function changeTurnos(validation){
            turnoChange1(validation);
            setTimeout(function(){
                turnoChange2(validation);
            },200)
        }

        function turnoChange1(validation){
            if (validation == 1) {
                validations1 = validation;
                if(document.getElementById('cola_1').value == ''){
                    Livewire.emit('turnoCola1');
                }
                var twoMinutes = 60 * 2,
                    display1 = document.querySelector('#time1'),
                    validation;
                startTimer1(twoMinutes, display1, validation);
                document.getElementById('ini').disabled=true;
                document.getElementById('fin').disabled=false;
            } else {
                validations1 = validation;
                Livewire.emit('turnoPerdido');
                document.getElementById('ini').disabled=false;
                document.getElementById('fin').disabled=true;
            }
        }

        function turnoChange2(validation) {
            if (validation == 1) {
                validations2 = validation;
                setTimeout(function (){
                    if(document.getElementById('cola_2').value == ''){
                        Livewire.emit('turnoCola2');
                    }
                    var threeMinutes = 60 * 3,
                    display2 = document.querySelector('#time2'),
                    validation;
                    startTimer2(threeMinutes, display2, validation);
                    document.getElementById('ini').disabled=true;
                    document.getElementById('fin').disabled=false;
                }, 200);
            } else {
                validations2 = validation;
                Livewire.emit('turnoPerdido');
                document.getElementById('ini').disabled=false;
                document.getElementById('fin').disabled=true;
            }
        };

        function startTimer1(duration, display) {
            duraciong=duration;
            timer1 = duraciong;
            var interval1 = setInterval(function () {
                if (validations1 == 1) {
                    getTime(display);
                } else {
                    clearInterval(interval1);
                    display.textContent = '02' + ":" + '00';
                }
            }, 1000);
        }

        function getTime(display){
            minutes = parseInt(timer1 / 60, 10);
            seconds = parseInt(timer1 % 60, 10);
            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;
            display.textContent = minutes + ":" + seconds;
            if (--timer1 < 0) {
                Livewire.emit('turnoCola1');
                timer1 = duraciong;
            }
        }

        function startTimer2(duration, display) {
            durationg2=duration;
            timer2 = durationg2;
            var interval2 = setInterval(function () {
                if (validations2 == 1) {
                    getTime2(display);
                }else{
                    clearInterval(interval2);
                    display.textContent = '03' + ":" + '00';
                }
            }, 1000);
        }

        function getTime2(display){
            if(document.getElementById('cola_2').value != ''){
                minutes2 = parseInt(timer2 / 60, 10);
                seconds2 = parseInt(timer2 % 60, 10);
                minutes2 = minutes2 < 10 ? "0" + minutes2 : minutes2;
                seconds2 = seconds2 < 10 ? "0" + seconds2 : seconds2;
                display.textContent = minutes2 + ":" + seconds2;
                if (--timer2 < 0) {
                    setTimeout(function (){
                            Livewire.emit('turnoCola2');
                    },200)
                    timer2 = durationg2;
                }
            }else{
                display.textContent = '03' + ":" + '00';
            }
        }

        function detenerTime(){
            if(validations1 == 1 ){
                if(document.getElementById('cola_1').value == ''){
                    console.log('entro 1');
                   timer1 = 120;
                    Livewire.emit('turnoCola1');
                }else{
                    setTimeout(function () {
                        if(document.getElementById('cola_2').value == '' && validations2==1){
                            console.log('entro 2');
                            timer2 = 180;
                            Livewire.emit('turnoCola2');
                        }
                    },500)
                }
            }
        }

    </script>

@endpush
