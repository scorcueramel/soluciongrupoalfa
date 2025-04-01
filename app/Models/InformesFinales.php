<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformesFinales extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'persona_id',
        'educacion',
        'historia_financiera',
        'alcohol',
        'drogas_ilegales',
        'antecedentes',
        'vinculos',
        'planes_infiltracion',
        'proyeccion_tiempo_empresa',
        'experiencia_laboral',
        'idoneidad_examinado',
        'repaso_preguntas_evaluado',
        'preguntas_relevantes',
        'conclusion',
    ];
}
