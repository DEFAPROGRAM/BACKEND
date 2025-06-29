<?php namespace App\Http\Controllers; use Illuminate\Http\Request; use Illuminate\Support\Facades\DB; use App\Models\Sala; use App\Models\Sede; use App\Models\Juzgado; use App\Models\Reserva; use App\Models\User; use Carbon\Carbon; use Maatwebsite\Excel\Excel; use Maatwebsite\Excel\Concerns\FromCollection; use Maatwebsite\Excel\Concerns\WithHeadings; use Dompdf\Dompdf; class ReportesController extends Controller { public function getListados(Request $request) { try { $tipo = $request->get("tipo"); switch ($tipo) { case "salas": $data = Sala::with("sede")->get(); break; case "sedes": $data = Sede::all(); break; case "juzgados": $data = Juzgado::with("sede")->get(); break; case "usuarios": $data = User::with(["sede", "juzgado"])->get(); break; case "reservas": $data = Reserva::with(["sala", "juzgado", "usuario"])->get(); break; default: return response()->json(["error" => "Tipo de reporte no válido"], 400); } return response()->json(["success" => true, "data" => $data, "total" => $data->count()]); } catch (\Exception $e) { return response()->json(["error" => "Error al generar reporte: " . $e->getMessage()], 500); } } public function getReservasPorFecha(Request $request) { try { $fechaInicio = $request->get("fecha_inicio"); $fechaFin = $request->get("fecha_fin"); $horaInicio = $request->get("hora_inicio"); $horaFin = $request->get("hora_fin"); $query = Reserva::with(["sala", "juzgado", "usuario"]); if ($fechaInicio && $fechaFin) { $query->whereBetween("fecha", [$fechaInicio, $fechaFin]); } if ($horaInicio && $horaFin) { $query->whereBetween("hora_inicio", [$horaInicio, $horaFin]); } $reservas = $query->get(); return response()->json(["success" => true, "data" => $reservas, "total" => $reservas->count(), "filtros" => ["fecha_inicio" => $fechaInicio, "fecha_fin" => $fechaFin, "hora_inicio" => $horaInicio, "hora_fin" => $horaFin]]); } catch (\Exception $e) { return response()->json(["error" => "Error al generar reporte: " . $e->getMessage()], 500); } } public function getReservasPorUsuario(Request $request) { try { $usuarioId = $request->get("usuario_id"); $periodo = $request->get("periodo", "mes"); $fechaInicio = $request->get("fecha_inicio"); $fechaFin = $request->get("fecha_fin"); $query = Reserva::with(["sala", "juzgado", "usuario"]); if ($usuarioId) { $query->where("id_usuario", $usuarioId); } if ($periodo === "semana") { $query->whereBetween("fecha", [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]); } elseif ($periodo === "mes") { $query->whereBetween("fecha", [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]); } elseif ($periodo === "año") { $query->whereBetween("fecha", [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]); } elseif ($fechaInicio && $fechaFin) { $query->whereBetween("fecha", [$fechaInicio, $fechaFin]); } $reservas = $query->get(); if (!$usuarioId) { $reservasPorUsuario = $reservas->groupBy("id_usuario")->map(function ($userReservas) { return ["usuario" => $userReservas->first()->usuario, "total_reservas" => $userReservas->count(), "reservas" => $userReservas]; }); return response()->json(["success" => true, "data" => $reservasPorUsuario->values(), "total_usuarios" => $reservasPorUsuario->count(), "total_reservas" => $reservas->count(), "periodo" => $periodo]); } return response()->json(["success" => true, "data" => $reservas, "total" => $reservas->count(), "usuario" => $reservas->first()->usuario ?? null, "periodo" => $periodo]); } catch (\Exception $e) { return response()->json(["error" => "Error al generar reporte: " . $e->getMessage()], 500); } } public function getReservasPorEstado(Request $request) { try { $estado = $request->get("estado"); $query = Reserva::with(["sala", "juzgado", "usuario"]); if ($estado) { $query->where("estado", $estado); } $reservas = $query->get(); if (!$estado) { $reservasPorEstado = $reservas->groupBy("estado")->map(function ($estadoReservas) { return ["estado" => $estadoReservas->first()->estado, "total" => $estadoReservas->count(), "reservas" => $estadoReservas]; }); return response()->json(["success" => true, "data" => $reservasPorEstado->values(), "total_reservas" => $reservas->count(), "resumen" => ["pendientes" => $reservas->where("estado", "pendiente")->count(), "confirmadas" => $reservas->where("estado", "confirmada")->count(), "canceladas" => $reservas->where("estado", "cancelada")->count()]]); } return response()->json(["success" => true, "data" => $reservas, "total" => $reservas->count(), "estado" => $estado]); } catch (\Exception $e) { return response()->json(["error" => "Error al generar reporte: " . $e->getMessage()], 500); } } public function getSalasMasSolicitadas(Request $request) { try { $limite = $request->get("limite", 10); $fechaInicio = $request->get("fecha_inicio"); $fechaFin = $request->get("fecha_fin"); $query = DB::table("reservas")->join("salas", "reservas.id_sala", "=", "salas.id_sala")->select("salas.id_sala", "salas.nom_sala", "salas.capacidad", DB::raw("COUNT(reservas.id_reserva) as total_reservas"), DB::raw("COUNT(CASE WHEN reservas.estado = \"confirmada\" THEN 1 END) as reservas_confirmadas"), DB::raw("COUNT(CASE WHEN reservas.estado = \"pendiente\" THEN 1 END) as reservas_pendientes"), DB::raw("COUNT(CASE WHEN reservas.estado = \"cancelada\" THEN 1 END) as reservas_canceladas"))->groupBy("salas.id_sala", "salas.nom_sala", "salas.capacidad")->orderBy("total_reservas", "desc")->limit($limite); if ($fechaInicio && $fechaFin) { $query->whereBetween("reservas.fecha", [$fechaInicio, $fechaFin]); } $salas = $query->get(); return response()->json(["success" => true, "data" => $salas, "total_salas" => $salas->count(), "filtros" => ["fecha_inicio" => $fechaInicio, "fecha_fin" => $fechaFin, "limite" => $limite]]); } catch (\Exception $e) { return response()->json(["error" => "Error al generar reporte: " . $e->getMessage()], 500); } } public function getEstadisticasGenerales() { try { $estadisticas = ["total_salas" => Sala::count(), "total_sedes" => Sede::count(), "total_juzgados" => Juzgado::count(), "total_usuarios" => User::count(), "total_reservas" => Reserva::count(), "reservas_por_estado" => ["pendientes" => Reserva::where("estado", "pendiente")->count(), "confirmadas" => Reserva::where("estado", "confirmada")->count(), "canceladas" => Reserva::where("estado", "cancelada")->count()], "reservas_este_mes" => Reserva::whereBetween("fecha", [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->count(), "reservas_esta_semana" => Reserva::whereBetween("fecha", [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count(), "sala_mas_solicitada" => DB::table("reservas")->join("salas", "reservas.id_sala", "=", "salas.id_sala")->select("salas.nom_sala", DB::raw("COUNT(*) as total"))->groupBy("salas.id_sala", "salas.nom_sala")->orderBy("total", "desc")->first()]; return response()->json(["success" => true, "data" => $estadisticas]); } catch (\Exception $e) { return response()->json(["error" => "Error al generar estadísticas: " . $e->getMessage()], 500); } }

public function getReportePersonalizado(Request $request) {
    try {
        $campos = $request->get("campos", []);
        $data = collect([]);

        foreach ($campos as $campo) {
            switch ($campo) {
                case "salas":
                    $data = $data->merge(Sala::with("sede")->get());
                    break;
                case "sedes":
                    $data = $data->merge(Sede::all());
                    break;
                case "juzgados":
                    $data = $data->merge(Juzgado::with("sede")->get());
                    break;
                case "usuarios":
                    $data = $data->merge(User::with(["sede", "juzgado"])->get());
                    break;
                case "reservas":
                    $data = $data->merge(Reserva::with(["sala", "juzgado", "usuario"])->get());
                    break;
            }
        }

        // Formatear datos para que sean consistentes
        $formattedData = $data->map(function ($item) use ($campo) {
            $formatted = [];
            
            if ($campo === "salas") {
                $formatted = [
                    "nom_sala" => $item->nom_sala,
                    "capacidad" => $item->capacidad,
                    "nom_sede" => $item->sede ? $item->sede->nom_sede : "Sede no asignada"
                ];
            } elseif ($campo === "sedes") {
                $formatted = [
                    "nom_sede" => $item->nom_sede,
                    "direccion" => $item->direccion,
                    "municipio" => $item->municipio
                ];
            } elseif ($campo === "juzgados") {
                $formatted = [
                    "nom_juzgado" => $item->nom_juzgado,
                    "nom_sede" => $item->sede ? $item->sede->nom_sede : "Sede no asignada"
                ];
            } elseif ($campo === "usuarios") {
                $formatted = [
                    "nombres" => $item->nombres,
                    "apellidos" => $item->apellidos,
                    "email" => $item->email,
                    "cargo" => $item->cargo,
                    "nom_sede" => $item->sede ? $item->sede->nom_sede : "Sede no asignada",
                    "nom_juzgado" => $item->juzgado ? $item->juzgado->nom_juzgado : "Juzgado no asignado",
                    "rol" => $item->rol
                ];
            } elseif ($campo === "reservas") {
                $formatted = [
                    "descripcion" => $item->descripcion,
                    "fecha" => $item->fecha->format("Y-m-d"),
                    "hora_inicio" => $item->hora_inicio->format("H:i"),
                    "hora_fin" => $item->hora_fin->format("H:i"),
                    "estado" => $item->estado,
                    "nom_sala" => $item->sala ? $item->sala->nom_sala : "Sala no asignada",
                    "usuario" => $item->usuario ? $item->usuario->nombres . " " . $item->usuario->apellidos : "Usuario no asignado"
                ];
            }
            
            return $formatted;
        });

        return response()->json([
            "success" => true,
            "data" => $formattedData->toArray(),
            "total" => $formattedData->count()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            "error" => "Error al generar reporte personalizado: " . $e->getMessage()
        ], 500);
    }
} public function exportarExcel(Request $request) { $data = $request->get("data"); $nombreArchivo = $request->get("nombre_archivo"); $headers = array( "Content-type" => "application/vnd.ms-excel", "Content-Disposition" => "attachment; filename=$nombreArchivo.xls", "Pragma" => "no-cache", "Cache-Control" => "must-revalidate, post-check=0, pre-check=0", "Expires" => "0" ); return response()->download($data, $nombreArchivo, $headers); } public function exportarPdf(Request $request) { $data = $request->get("data"); $nombreArchivo = $request->get("nombre_archivo"); $pdf = new Dompdf(); $pdf->loadHtml($data); $pdf->render(); $pdf->stream($nombreArchivo . ".pdf", array("Attachment" => 0)); } }
