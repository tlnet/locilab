<?php

namespace App\Http\Controllers\Admin;

use App\Models\Alelo;
use App\Models\Animal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Marcador;
use Illuminate\Support\Facades\Http;
use RealRashid\SweetAlert\Facades\Alert;

class AlelosController extends Controller
{

    public function __construct()
    {
        ini_set('max_execution_time', 8000);
    }
    public function index()
    {
        return view('admin.animais.alelos');
    }

    public function alelosCreate()
    {

        return view('admin.animais.alelos-create', get_defined_vars());
    }

    public function importTxt()
    {
        return view('admin.ordem-servico.import-txt');
    }

    public function alelosApi()
    {
        return view('admin.animais.alelos-api');
    }

    public function api(Request $request)
    {
        $response = Http::get('http://laboratorios.abccmm.org.br/api/Exames', ['registro' => $request->registro]);

        $data = $response->body();
        $data = json_decode($data, true);
        $data = collect($data);
        return $data;
    }
    public function store(Request $request)
    {
        // Obtém a resposta da API
        $response = Http::get('http://laboratorios.abccmm.org.br/api/Exames', ['registro' => $request->registro]);

        // Verifica se a resposta é bem-sucedida
        if ($response->successful()) {
            $data = $response->json();

            // Extrai os dados relevantes
            $animalData = $data['animal'];
            $exameData = $data['exame'];

            // Verifica se o animal já existe no banco de dados
            $animal = Animal::where('animal_name', $animalData['nomeAnimal'])->first();
            // \Log::info($animalData);
            $marcadores = Marcador::where('especie', 'EQUINA')->get();
            if ($animal) {
                if (!$animal->codlab) {
                    $animal->codlab = $this->generateUniqueCodlab('EQU');
                }

                // Atualiza o identificador do animal
                $animal->identificador = $exameData['codigo'] ?? null;

                $animal->save();
            } else {
                $sigla = 'EQU';
                $codlab = $this->generateUniqueCodlab($sigla);
                // Cria um novo animal no banco de dados
                $animal = Animal::create([
                    'animal_name' => $animalData['nomeAnimal'],
                    'especies' => 'EQUINA',
                    'breed' => 'MANGALARGA',
                    'sex' => $animalData['sexo'],
                    'birth_date' => $animalData['dataNascimento'],
                    'number_definitive' => $animalData['registro'],
                    'status' => 1,
                    'codlab' => $codlab,
                    'identificador' => $exameData['codigo'] ?? null,
                ]);
            }

            if ($exameData['alelos'] != null) {
                foreach ($marcadores as $marcador) {
                    $apiAlelos = collect($exameData['alelos'])->where('marcador', $marcador->gene)->first();
                    $alelo = Alelo::where('animal_id', $animal->id)
                        ->where('marcador', $marcador->gene)
                        ->first();
                    if ($apiAlelos) {
                        if ($alelo) {
                            // Atualiza o alelo se já existir
                            $alelo->alelo1 = $apiAlelos['alelo1'];
                            $alelo->alelo2 = $apiAlelos['alelo2'];
                            $alelo->lab = $exameData['laboratorio'];
                            $alelo->data = $exameData['dataResultado'];
                            $alelo->save();
                        } else {
                            // Cria um novo alelo se não existir
                            Alelo::create([
                                'animal_id' => $animal->id,
                                'marcador' => $marcador->gene,
                                'alelo1' => $apiAlelos['alelo1'],
                                'alelo2' =>   $apiAlelos['alelo2'],
                                'lab' => $exameData['laboratorio'],
                                'data' => $exameData['dataResultado'],
                            ]);
                        }
                    } else {
                        if ($alelo) {
                            // Atualiza o alelo se já existir
                            $alelo->alelo1 = '';
                            $alelo->alelo2 = '';
                            $alelo->lab = $exameData['laboratorio'];
                            $alelo->data = $exameData['dataResultado'];
                            $alelo->save();
                        } else {
                            // Cria um novo alelo se não existir
                            Alelo::create([
                                'animal_id' => $animal->id,
                                'marcador' => $marcador->gene,
                                'alelo1' => '',
                                'alelo2' =>   '',
                                'lab' => $exameData['laboratorio'],
                                'data' => $exameData['dataResultado'],
                            ]);
                        }
                    }
                }

                return response()->json(['success' => 'ok']);
            }

            return response()->json(['error' => 'vazio']);
        }

        return response()->json(['error' => 'erro']);
    }
    private function generateUniqueCodlab($sigla)
    {
        $maxNumber = Animal::selectRaw('MAX(CAST(SUBSTRING(codlab, 4) AS UNSIGNED)) as max_num')
            ->whereRaw('CAST(SUBSTRING(codlab, 4) AS UNSIGNED) >= 100000 AND CAST(SUBSTRING(codlab, 4) AS UNSIGNED) < 200000')
            ->first();

        $startValue = ($maxNumber && $maxNumber->max_num) ? $maxNumber->max_num + 1 : 100000;

        // Verifique a unicidade da parte numérica do codlab em todo o banco de dados
        while (Animal::whereRaw('CAST(SUBSTRING(codlab, 4) AS UNSIGNED) = ?', [$startValue])->exists()) {
            $startValue += 1;
        }

        return $sigla . strval($startValue);
    }
    public function getAnimal(Request $request)
    {
        $animal = Animal::with('alelos')->where('animal_name', $request->name)->first();
        $especie = $animal->especies; // Define 'EQUINA' como valor padrão se $animal->especies for null
        if ($especie != null) {
            $marcadores = Marcador::where('especie', $especie)->get();
        } else {
            $marcadores = [];
        }

        $view = view('admin.animais.includes.alelos-render', get_defined_vars())->render();
        if ($animal) {
            return response()->json(get_defined_vars());
        }

        return response()->json(['error' => 'erro']);
    }
    public function getAnimalCodlab(Request $request)
    {
        $animal = Animal::with('alelos')->where('codlab', $request->codlab)->first();
        $especie = $animal->especies; // Define 'EQUINA' como valor padrão se $animal->especies for null
        if ($especie != null) {
            $marcadores = Marcador::where('especie', $especie)->get();
        } else {
            $marcadores = [];
        }

        $view = view('admin.animais.includes.alelos-render', get_defined_vars())->render();
        if ($animal) {
            return response()->json(get_defined_vars());
        }

        return response()->json(['error' => 'erro']);
    }

    public function storeAlelo(Request $request)
    {
        $animal = Animal::with('alelos')->where('animal_name', $request->animal_name)->first();

        if ($animal) {
            // Verifica se já existem alelos relacionados ao animal
            if ($animal->alelos->isNotEmpty()) {
                $alelos1 = $request->input('alelo1', []);
                $alelos2 = $request->input('alelo2', []);

                $alelos1 = array_map('strtoupper', $alelos1);
                $alelos2 = array_map('strtoupper', $alelos2);

                foreach ($animal->alelos as $alelo) {
                    $key = array_search($alelo->marcador, $request->marcador);

                    // Verifica se o campo 'alelo1' ou 'alelo2' está preenchido
                    if ($key !== false) {
                        $alelo->update([
                            'alelo1' => $alelos1[$key] !== null ? $alelos1[$key] : '',
                            'alelo2' => $alelos2[$key] !== null ? $alelos2[$key] : '',
                            'lab' => $request->input('lab'),
                            'data' => $request->input('data'),
                        ]);
                    }
                }
            } else {
                // Se não existirem alelos, cria novos registros
                $alelos1 = $request->input('alelo1', []);
                $alelos2 = $request->input('alelo2', []);

                $alelos1 = array_map('strtoupper', $alelos1);
                $alelos2 = array_map('strtoupper', $alelos2);

                foreach ($alelos1 as $key => $item) {
                    // Verifica se o campo 'alelo1' ou 'alelo2' está preenchido
                    if ($item !== null || $alelos2[$key] !== null) {
                        Alelo::create([
                            'animal_id' => $animal->id,
                            'marcador' => $request->input('marcador.' . $key),
                            'alelo1' => $item !== null ? $item : '',
                            'alelo2' => $alelos2[$key] !== null ? $alelos2[$key] : '',
                            'lab' => $request->input('lab'),
                            'data' => $request->input('data'),
                        ]);
                    }
                }
            }

            if ($animal->identificador) {
                $animal->update([
                    'identificador' => $request->input('identificador') ? $request->input('identificador') : 'LO23-' . substr($animal->codlab, 3),
                ]);
            } else {
                $animal->update([
                    'identificador' => $request->input('identificador') ? $request->input('identificador') : 'LO23-' . substr($animal->codlab, 3),
                ]);
            }

            return response()->json(['success' => 'ok']);
        }
    }

    public function destroyAlelos(Request $request)
    {
        $alelos = Alelo::where('animal_id', $request->id)->get();

        if ($alelos) {
            foreach ($alelos as $alelo) {
                $alelo->delete();
            }

            return response()->json(['success' => 'ok']);
        }
    }
}
