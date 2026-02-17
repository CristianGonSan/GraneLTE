<?php

namespace Database\Seeders\Inventory;

use App\Models\Inventory\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories =
            [
                [
                    'name' => 'Sin categoría',
                    'description' => 'Material sin categoría definida.'
                ],
                [
                    'name' => 'Forrajes',
                    'description' => 'Alimentos fibrosos como pastos, henos y ensilajes que proporcionan fibra y energía al ganado.'
                ],
                [
                    'name' => 'Concentrados',
                    'description' => 'Ingredientes ricos en energía y proteína, como granos y subproductos, para mejorar el rendimiento.'
                ],
                [
                    'name' => 'Suplementos Minerales',
                    'description' => 'Fuentes de minerales esenciales como calcio, fósforo y sal para cubrir deficiencias nutricionales.'
                ],
                [
                    'name' => 'Aditivos',
                    'description' => 'Sustancias como probióticos, prebióticos o promotores de crecimiento para optimizar la salud y digestión.'
                ],
                [
                    'name' => 'Subproductos Agrícolas',
                    'description' => 'Residuos de cultivos como bagazo, pulpa o cascarilla, utilizados como complemento en la dieta.'
                ],
                [
                    'name' => 'Forrajes Frescos',
                    'description' => 'Pastos verdes y cultivos forrajeros frescos ricos en fibra y nutrientes para consumo directo.'
                ],
                [
                    'name' => 'Henos',
                    'description' => 'Forrajes secos como alfalfa o gramíneas, ideales para almacenamiento y suministro de fibra.'
                ],
                [
                    'name' => 'Ensilajes',
                    'description' => 'Forrajes fermentados como maíz o sorgo, conservados para proporcionar energía y mejorar la digestibilidad.'
                ],
                [
                    'name' => 'Granos',
                    'description' => 'Cereales como maíz, trigo o cebada, ricos en carbohidratos para aportar energía.'
                ],
                [
                    'name' => 'Concentrados Proteicos',
                    'description' => 'Ingredientes como harina de soya o canola, con alto contenido de proteína para el crecimiento muscular.'
                ],
                [
                    'name' => 'Suplementos Minerales',
                    'description' => 'Mezclas de minerales como calcio, fósforo, magnesio y sal para equilibrar la dieta.'
                ],
                [
                    'name' => 'Suplementos Vitamínicos',
                    'description' => 'Fuentes de vitaminas A, D, E y del complejo B para apoyar el metabolismo y la salud.'
                ],
                [
                    'name' => 'Aditivos Funcionales',
                    'description' => 'Probióticos, prebióticos o enzimas que mejoran la digestión y la salud intestinal.'
                ],
                [
                    'name' => 'Subproductos Agrícolas',
                    'description' => 'Residuos como bagazo de caña, pulpa de cítricos o cascarilla de arroz, usados como fuente de fibra.'
                ],
                [
                    'name' => 'Subproductos de Molinería',
                    'description' => 'Derivados como salvado de trigo o gluten de maíz, ricos en nutrientes y económicos.'
                ],
                [
                    'name' => 'Grasas y Aceites',
                    'description' => 'Fuentes de energía densa como aceite de palma o sebo para aumentar el valor calórico de la dieta.'
                ],
                [
                    'name' => 'Melazas',
                    'description' => 'Subproductos azucarados que aportan energía y mejoran la palatabilidad de las raciones.'
                ],
                [
                    'name' => 'Forrajes de Leguminosas',
                    'description' => 'Cultivos como alfalfa o trébol, ricos en proteína y fibra para dietas equilibradas.'
                ],
                [
                    'name' => 'Urea y Suplementos Nitrogenados',
                    'description' => 'Fuentes de nitrógeno no proteico para mejorar la síntesis de proteína microbiana en el rumen.'
                ],
                [
                    'name' => 'Residuos de Matadero',
                    'description' => 'Harinas de carne, hueso o sangre, usadas como fuentes de proteína y minerales.'
                ]
            ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']], // campo único de búsqueda
                ['description' => $category['description']]
            );
        }
    }
}
