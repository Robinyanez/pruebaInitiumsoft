<?php

namespace App\Http\Livewire\Users;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Turno;
use App\Models\User;
use DB;
use Str;

class CreateUser extends Component
{
    use WithPagination;

    /* variables publicas */
    public $codigo_turno,
            $user_id1,
            $user_id2,
            $name,
            $name_cola1,
            $name_cola2,
            $cola1 = 1,
            $cola2 = 2;

    /* varibles publicas de consulta para accedor cuando se necesite */
    public $turnoPendienteQuery = [];
    public $turnoAtendidoQuery = [];

    /* paguinacion con bootstrap */
    protected $paginationTheme = 'bootstrap';

    /* escuchadores para comunicarse entre javascript y liveware
    *   y ejecutar las funciones para cambiar los tiempos de atencion
    */
    protected $listeners = [
        'turnoCola1'    => 'changeColaP',
        'turnoCola2'    => 'changeColaS',
        'turnoPerdido'  => 'turnoPerdido'
    ];

    /* metodos de validacion */
    protected $rules = [
        'name'  => 'required',
    ];

    /* metodo para cambiar los mesajes de validacion */
    protected $messages = [
        'name.required' => 'El Nombre es requerido.',
    ];

    /* metodo para validacion de campos en tiempo real */
    public function updated($propertyName){

        $this->validateOnly($propertyName);
    }

    /* metodo para cargar funciones al momento de iniciar la paguina */
    public function mount(){

        $this->turnoCodigo();
        $this->turnoPerdido();
    }

    /* metodo apra renderizar las consultas */
    public function render()
    {

        /* Consultas para las tablas con paginación */
        $turno_pendiente = DB::table('users as u')
                            ->select('u.name','t.codigo','t.estado')
                            ->join('turnos as t','u.id','=','t.user_id')
                            ->where('t.estado',1)
                            ->orderBy('t.id','asc')
                            ->paginate(5);

        $turno_atendido = DB::table('users as u')
                            ->select('u.name','t.codigo','t.estado','t.cola')
                            ->join('turnos as t','u.id','=','t.user_id')
                            ->where('t.estado',3)
                            ->orderBy('t.id','asc')
                            ->paginate(5);

        /* Consultas para trabajar los turnos */
        $this->turnoPendienteQuery = DB::table('users as u')
                                        ->select('u.name','t.codigo','t.estado','t.user_id')
                                        ->join('turnos as t','u.id','=','t.user_id')
                                        ->where('t.estado',1)
                                        ->orderBy('t.id','asc')
                                        ->get();

        $this->turnoAtendidoQuery = DB::table('users as u')
                                        ->select('u.name','t.codigo','t.estado')
                                        ->join('turnos as t','u.id','=','t.user_id')
                                        ->where('t.estado',3)
                                        ->orderBy('t.id','asc')
                                        ->get();

        /* cargamos el codigo del turno */
        $this->turnoCodigo();

        /* retornamos la vista */
        return view('livewire.users.create-user',compact('turno_pendiente','turno_atendido'));
    }

    /* Metodo para crear turno */
    public function store()
    {

        $this->validate();

        try
        {
            DB::beginTransaction();

            $user = new User();
            $user->name = Str::title($this->name);
            $user->save();

            $turno = new Turno();
            $turno->codigo = $this->codigo_turno;
            $turno->user_id = $user->id;
            $turno->estado = 1;
            $turno->save();

            $this->resetInput();

            DB::commit();

        }catch (Exception $e) {

            DB::rollBack();

        }
    }

    /* metodo para agregar los turno a la cola 1 */
    public function changeColaP()
    {
        /* validamos si el el campo de atencion esta ocupado */
        if ($this->name_cola1) {
            /* alterminar el tiempo cambie de estado a atendido */
            $turnoChangeP = Turno::findOrFail($this->user_id1);
            $turnoChangeP->update([
                'estado'    => 3
            ]);
            /* hacemos un conteo para ver si hay datos pendientes de atencion */
            if ($this->turnoPendienteQuery->count()) {
                /* agregamos los datos pendientes al campo de la cola 1 */
                $this->name_cola1 = $this->turnoPendienteQuery[0]['name'];
                $this->user_id1 = $this->turnoPendienteQuery[0]['user_id'];
                /* actualizamos su estodo cuando esta siento atendido */
                $turnoChangeP = Turno::findOrFail($this->user_id1);
                $turnoChangeP->update([
                    'cola'      => $this->cola1,
                    'estado'    => 2
                ]);
            /* si no hay datos limpiamos las variables de la cola 1 */
            } else {

                $this->name_cola1 = null;
                $this->user_id = null;
            }

        } else {
            /* hacemos un conteo para ver si hay datos pendientes de atencion */
            if ($this->turnoPendienteQuery->count()) {
                /* agregamos los datos pendientes al campo de la cola 1 */
                $this->name_cola1 = $this->turnoPendienteQuery[0]['name'];
                $this->user_id1 = $this->turnoPendienteQuery[0]['user_id'];
                /* actualizamos su estodo cuando esta siento atendido */
                $turnoChangeP = Turno::findOrFail($this->user_id1);
                $turnoChangeP->update([
                    'cola'      => $this->cola1,
                    'estado'    => 2
                ]);
            /* si no hay datos limpiamos las variables de la cola 1 */
            } else {

                $this->name_cola1 = null;
                $this->user_id = null;
            }
        }
    }

    /* metodo para agregar los turno a la cola 2 */
    public function changeColaS()
    {
        /* validamos si el el campo de atencion esta ocupado */
        if ($this->name_cola2) {
            /* alterminar el tiempo cambie de estado a atendido */
            $turnoChangeP = Turno::findOrFail($this->user_id2);
            $turnoChangeP->update([
                'estado'    => 3
            ]);
            /* hacemos un conteo para ver si hay datos pendientes de atencion */
            if ($this->turnoPendienteQuery->count()) {
                /* agregamos los datos pendientes al campo de la cola 2 */
                $this->name_cola2 = $this->turnoPendienteQuery[0]['name'];
                $this->user_id2 = $this->turnoPendienteQuery[0]['user_id'];
                /* actualizamos su estodo cuando esta siento atendido */
                $turnoChangeP = Turno::findOrFail($this->user_id2);
                $turnoChangeP->update([
                    'cola'      => $this->cola2,
                    'estado'    => 2
                ]);
            /* si no hay datos limpiamos las variables de la cola 2 */
            } else {

                $this->name_cola2 = null;
                $this->user_id = null;
            }
        /* si el campo esta vacio */
        } else {
            /* hacemos un conteo para ver si hay datos pendientes de atencion */
            if ($this->turnoPendienteQuery->count()) {
                /* agregamos los datos pendientes al campo de la cola 2 */
                $this->name_cola2 = $this->turnoPendienteQuery[0]['name'];
                $this->user_id2 = $this->turnoPendienteQuery[0]['user_id'];
                /* actualizamos su estodo cuando esta siento atendido */
                $turnoChangeP = Turno::findOrFail($this->user_id2);
                $turnoChangeP->update([
                    'cola'      => $this->cola2,
                    'estado'    => 2
                ]);
            /* si no hay datos limpiamos las variables de la cola 2 */
            } else {

                $this->name_cola2 = null;
                $this->user_id = null;
            }
        }
    }

    /* metodo para vaciar los campo de ingreso */
    public function resetInput(){

        $this->codigo_turno = null;
        $this->name         = null;
    }

    /* metodo para obtener el codigo "Tur-..." */
    public function turnoCodigo()
    {
        $obtCodigo = Turno::select('id')->orderBy('id','desc')->get();

        if ($obtCodigo->count()) {

            $sum = $obtCodigo[0]['id'] + 1;
            $this->codigo_turno = 'Tur-'.$sum;

        } else {
            $this->codigo_turno = 'Tur-1';
        }

    }

    /* metodo para actualizar los datos a pendientes en caso de algun error */
    public function turnoPerdido()
    {
        $turnoPer = Turno::where('estado',2)->get();

        if ($turnoPer->count()) {
            foreach ($turnoPer as $item) {
                $turnoChangeP = Turno::findOrFail($item->user_id);
                $turnoChangeP->update([
                    'estado'    => 1
                ]);
            }
        }

        $this->name_cola1 = null;
        $this->name_cola2 = null;
        $this->user_id = null;
    }
}
