<?php

namespace App\Http\Controllers\info_arbitral;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\MPartido;
use App\Http\Models\MEquipo;
use App\Http\Models\MTorneo;
use App\Http\Models\MJugador;
use App\Http\Models\MFase;


class partidos_controller extends Controller
{
    public function partidos_hoy($fecha,$fase,$torneo){
       $Partidos = MPartido::select('*')
       ->where('fecha',$fecha)->where('tipo_fase',$fase)->where('id_torneo',$torneo)
       ->get(); 

       foreach($Partidos as  $valor) {
            $Equipos1 = MEquipo::where('id_equipo','=',$valor->equipo_local)->first();
            $valor->equipo_local=$Equipos1->nombre;

            $Equipos2 = MEquipo::where('id_equipo','=',$valor->equipo_visitante)->first();
            $valor->equipo_visitante=$Equipos2->nombre;
           // print "\n"."equipos".$valor;
       }
       return $Partidos;
    }
    public function fases_x_categoria($id) {
       //print "\n".$id;
        $enviar = MTorneo::where('id_torneo',$id)->take(1)->first();
        return view('info_arbitral/fases')->with('torneo',$enviar);
    
    }

    public function getCategorias() {
        $Torneos = MTorneo::select('*')->where('elimnado',false)->get();
        return $Torneos;
    }

    /*Este metodo devuelve la vista del formulario para registrar
    los goles, los jugadores titulares y los jugadores suplentes*/
    public function verRegistro()
    {
        return view("info_arbitral\goles");
    }

    public function fases_categoria($Torneo) {
        $enviar = MPartido::select('tipo_fase')->where('id_torneo',$Torneo)->groupBy('tipo_fase')->get();
        return $enviar;
    }

    public function calendario_x_fase($fase,$torneo) {
        $enviar = MPartido::where('tipo_fase',$fase)->where('id_torneo',$torneo)->take(1)->first();
        //print "\n".$enviar;
        return view('info_arbitral/calendario')->with('torneo',$enviar);
    
    }


    public function verRegistro2($partido)
    {
        $Partido = MPartido::where('id_partido',$partido)
        ->take(1)->first();
        print "\n"."   fecha  ".$Partido->fecha;
        print "\n"."   lugar  ".$Partido->lugar;
        print "\n"."   equipo1  ".$Partido->equipo_local;
        print "\n"."   equipo2  ".$Partido->equipo_visitante;
        //return view("info_arbitral\goles");
        return view('info_arbitral/goles')->with('elocal',$Partido->equipo_local);
    }
}
?>