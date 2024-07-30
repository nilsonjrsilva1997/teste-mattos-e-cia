<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EstrategiaWMS;
use App\Models\EstrategiaWMSHorarioPrioridade;

class EstrategiaWMSController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'dsEstrategia' => 'required|string',
            'nrPrioridade' => 'required|integer',
            'horarios' => 'required|array',
            'horarios.*.dsHorarioInicio' => 'required|string|size:5',
            'horarios.*.dsHorarioFinal' => 'required|string|size:5',
            'horarios.*.nrPrioridade' => 'required|integer',
        ]);

        $estrategiaWMS = EstrategiaWMS::create([
            'ds_estrategia_wms' => $request->dsEstrategia,
            'nr_prioridade' => $request->nrPrioridade,
            'dt_registro' => now(),
        ]);

        $cdEstrategiaWMS = $estrategiaWMS->cd_estrategia_wms;

        foreach ($request->horarios as $horario) {
            EstrategiaWMSHorarioPrioridade::create([
                'cd_estrategia_wms' => $cdEstrategiaWMS,
                'ds_horario_inicio' => $horario['dsHorarioInicio'],
                'ds_horario_final' => $horario['dsHorarioFinal'],
                'nr_prioridade' => $horario['nrPrioridade'],
                'dt_registro' => now(),
            ]);
        }

        $estrategiaWMSWithHorarios = EstrategiaWMS::with('horarios')->find($cdEstrategiaWMS);

        return response()->json([
            'message' => 'Registro criado com sucesso!',
            'data' => $estrategiaWMSWithHorarios
        ], 201);
    }

    public function getPrioridade($cdEstrategia, $dsHora, $dsMinuto)
    {
        if (!preg_match('/^(0[0-9]|1[0-9]|2[0-3]):([0-5][0-9])$/', "{$dsHora}:{$dsMinuto}")) {
            return response()->json(['message' => 'Formato de hora inválido'], 400);
        }

        $estrategia = EstrategiaWMS::find($cdEstrategia);

        if (!$estrategia) {
            return response()->json(['message' => 'Estratégia não encontrada'], 404);
        }

        $horario = EstrategiaWMSHorarioPrioridade::where('cd_estrategia_wms', $cdEstrategia)
            ->where('ds_horario_inicio', '<=', "{$dsHora}:{$dsMinuto}")
            ->where('ds_horario_final', '>=', "{$dsHora}:{$dsMinuto}")
            ->orderBy('nr_prioridade', 'desc')
            ->first();

        $prioridade = $horario ? $horario->nr_prioridade : $estrategia->nr_prioridade;

        return response()->json([
            'prioridade' => $prioridade,
        ]);
    }
}