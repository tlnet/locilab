<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Exam;
use App\Models\Animal;
use App\Models\OrderLote;
use App\Models\OrdemServico;
use App\Models\OrderRequest;
use Illuminate\Http\Request;
use App\Models\OrderRequestPayment;
use App\Http\Controllers\Controller;
use App\Models\Alelo;
use App\Models\DnaVerify;
use App\Models\Marcador;
use Picqer\Barcode\BarcodeGeneratorPNG;


class OrdemServicoController extends Controller
{
    public function store(Request $request)
    {
        $order = OrderRequest::find($request->order);

        if (!$order) {
            // Ordem não encontrada
            return response()->json(['error' => 'Pedido não encontrado.'], 404);
        }

        // Verificar se já existe uma ordem de serviço com o order_id
        $existingOrder = OrdemServico::where('order', $order->id)->first();
        if ($existingOrder) {
            // Já existe uma ordem de serviço com o order_id
            return response()->json(['error' => 'Já existe uma ordem de serviço para este pedido.'], 400);
        }

        $orderRequest = OrderRequestPayment::where('order_request_id', $order->id)->get();
        $lote = OrderLote::create([
            'order_id' => $order->id,
            'owner' => $order->creator,
        ]);

        foreach ($orderRequest as $item) {
            $exame = Exam::find($item->exam_id);
            $animal = Animal::find($item->animal_id);
            $data = Carbon::parse($item->updated_at)->addWeekdays($exame->days);
            $randomNumber = mt_rand(0, 1000000);
            $dna_verify = DnaVerify::where('animal_id', $item->animal_id)->latest('created_at')->first();
            if (!$dna_verify) {
                switch ($animal->especies) {
                    case 'EQUINA':
                        $tipo = 'EQUTR';
                        break;
                    case 'MUARES':
                        $tipo = 'MUATR';
                        break;
                    case 'ASININO':
                        $tipo = 'ASITR';
                        break;
                    case 'EQUINO_PEGA':
                        $tipo = 'ASITR';
                        break;
                    case 'BOVINA':
                        $tipo = 'BOVTR';
                        break;
                    default:
                        $tipo = 'EQUTR';
                }
                $dna_verify = DnaVerify::create([
                    'animal_id' => $item->animal_id,
                    'order_id' => $order->id,
                    'verify_code' => $tipo,
                ]);
            }
            $sigla = substr($animal->especies, 0, 3) ? substr($animal->especies, 0, 3) : 'EQU';
            if ($animal->codlab == null) {
                $animal->update([
                    'codlab' => $sigla . strval($this->generateUniqueCodlab()),
                ]);
            }

            $ordemServico = OrdemServico::create([
                'order' => $order->id,
                'animal_id' => $animal->id,
                'owner_id' => $order->owner_id,
                'lote' => $lote->id,
                'animal' => $animal->animal_name,
                'codlab' => $animal->codlab,
                'id_abccmm' => $animal->register_number_brand,
                'tipo_exame' => $dna_verify->verify_code,
                'proprietario' => $order->creator,
                'tecnico' => $order->technical_manager,
                'data' => $data,
                'data_payment' => $item->updated_at,
                'rg_pai' => $animal->registro_pai,
                'rg_mae' => $animal->registro_mae,
                'status' => 1,
            ]);
        }

        return response()->json('success', 200);
    }
    private function generateUniqueCodlab()
    {
        $startValue = 100000;
        $codlab = Animal::max('codlab');

        if ($codlab >= $startValue) {
            if (is_numeric($codlab)) {
                $codlab = intval($codlab);
            } else {
                $codlab = $startValue;
            }
            $codlab += 1;
        } else {
            $codlab = $startValue;
        }


        while (Animal::where('codlab', $codlab)->exists()) {
            $codlab += 1;
        }

        return $codlab;
    }

    public function index()
    {
        $ordemServicos = OrderLote::paginate(10);
        return view('admin.ordem-servico.index', get_defined_vars());
    }

    public function show($id)
    {
        $ordem = OrderLote::find($id);
        $ordemServicos = OrdemServico::where('lote', $id)->get();
        return view('admin.ordem-servico.show', get_defined_vars());
    }

    public function importFile(Request $request)
    {
        // Verificar se um arquivo foi enviado
        if ($request->hasFile('file')) {
            // Obter o arquivo do campo de entrada
            $file = $request->file('file');

            // Verificar se o arquivo é válido
            if ($file->isValid()) {
                // Caminho para salvar o arquivo
                $filePath = storage_path('app/files/') . $file->getClientOriginalName();

                // Mover o arquivo para o diretório desejado
                $file->move(storage_path('app/files'), $file->getClientOriginalName());

                // Ler o conteúdo do arquivo
                $fileContent = file_get_contents($filePath);

                // Quebrar o conteúdo do arquivo em linhas
                $lines = explode("\n", $fileContent);

                // Iterar pelas linhas do arquivo
                foreach ($lines as $line) {
                    // Quebrar a linha em colunas separadas por tabulação
                    $columns = explode("\t", $line);

                    // Verificar se a coluna com o índice 1 existe
                    if (isset($columns[1])) {
                        $sampleName = $columns[1];
                        $animal = Animal::where('codlab', $sampleName)->first();
                        if ($animal) {
                            // Remover espaços e asteriscos dos valores dos alelos
                            $marcador = trim(str_replace('*', '', $columns[2]));
                            $alelo1 = trim(str_replace('*', '', $columns[3]));
                            $alelo2 = trim(str_replace('*', '', $columns[4]));

                            // Criar o registro de Alelo para o animal encontrado
                            $alelo = Alelo::create([
                                'animal_id' => $animal->id,
                                'marcador' => $marcador,
                                'alelo1' => $alelo1,
                                'alelo2' => $alelo2,
                            ]);
                        }
                    } else {
                        // Tratar o caso em que a colunsa não existe
                        $sampleName = null; // Ou qualquer outro valor padrão que faça sentido para o seu caso
                    }
                }

                // Retorne uma resposta adequada após a importação
                return redirect()->back()->with('success', 'Arquivo importado com sucesso');
            }
        }

        // Caso nenhum arquivo tenha sido enviado ou o arquivo seja inválido
        return response()->json(['message' => 'Nenhum arquivo válido enviado'], 400);
    }

    public function compareAlelo($id)
    {
        $ordem = OrdemServico::find($id);
        $animal = Animal::with('alelos')->find($ordem->animal_id);
        $dna_verify = DnaVerify::where('animal_id', $ordem->animal_id)->first();
        $sigla = substr($animal->especies, 0, 3);
        $pai = null;
        $mae = null;

        switch ($dna_verify->verify_code) {
            case $sigla . 'PD':
                $pai = Animal::with('alelos')->where('animal_name', $animal->pai)->first();
                break;
            case $sigla . 'MD':
                $mae = Animal::with('alelos')->where('animal_name', $animal->mae)->first();
                break;
            case $sigla . 'TR':
                $pai = Animal::with('alelos')->where('animal_name', $animal->pai)->first();
                $mae = Animal::with('alelos')->where('animal_name', $animal->mae)->first();
                break;
            default:
                break;
        }


        return view('admin.ordem-servico.alelo-compare', get_defined_vars());
    }

    public function dataBarCode(Request $request)
    {
        $ordem = OrdemServico::find($request->ordem_id);
        $ordem->update([
            'data_bar' => $request->data
        ]);
        return response()->json($ordem);
    }

    public function analise(Request $request)
    {
        $ordem = OrdemServico::find($request->ordem);
        $animal = Animal::with('alelos')->find($ordem->animal_id);
        $dna_verify = DnaVerify::where('animal_id', $animal->id)->first();
        $sigla = substr($animal->especies, 0, 3);
        $pai = null;
        $mae = null;

        switch ($dna_verify->verify_code) {
            case $sigla . 'PD':
                $pai = Animal::with('alelos')->where('animal_name', $animal->pai)->first();
                break;
            case $sigla . 'MD':
                $mae = Animal::with('alelos')->where('animal_name', $animal->mae)->first();
                break;
            case $sigla . 'TR':
                $pai = Animal::with('alelos')->where('animal_name', $animal->pai)->first();
                $mae = Animal::with('alelos')->where('animal_name', $animal->mae)->first();
                break;
            default:
                break;
        }

        $alelosMae = [];
        $alelosPai = [];
        $laudoMae = [];
        $laudoMaeExclui = [];
        $alelosPai = [];
        $laudoGeral = [];

        // Comparar alelos entre mãe e animal
        if ($mae != null) {
            foreach ($animal->alelos as $animalAlelo) {
                foreach ($mae->alelos as $maeAlelo) {
                    if ($animalAlelo->marcador == $maeAlelo->marcador) {
                        $alelosMae[] = [
                            'marcador' => $animalAlelo->marcador,
                            'alelo1' => $animalAlelo->alelo1,
                            'alelo2' => $animalAlelo->alelo2,
                            'aleloMae1' => $maeAlelo->alelo1,
                            'aleloMae2' => $maeAlelo->alelo2,
                        ];
                        break;
                    }
                }
            }
            foreach ($alelosMae as $maeAl) {
                if (($maeAl['alelo1'] != '' || $maeAl['alelo2'] != '') && ($maeAl['aleloMae1'] != '' || $maeAl['aleloMae2'] != '')) {
                    if (
                        $maeAl['alelo1'] == $maeAl['aleloMae1'] ||
                        $maeAl['alelo1'] == $maeAl['aleloMae2'] ||
                        $maeAl['alelo2'] == $maeAl['aleloMae1'] ||
                        $maeAl['alelo2'] == $maeAl['aleloMae2']
                    ) {
                        $laudoMae[] = [
                            'marcador' => $maeAl['marcador'],
                            'include' => 'M'
                        ];
                        \Log::info("Condição 1 cumprida" . $maeAl['marcador']);
                    } else {
                        $laudoMae[] = [
                            'marcador' => $maeAl['marcador'],
                            'include' => ''
                        ];
                        \Log::info("Condição 2 cumprida" . $maeAl['marcador']);
                    }
                } elseif ($maeAl['alelo1'] == '' && $maeAl['alelo2'] == '' || empty($maeAl['aleloMae1']) && empty($maeAl['aleloMae2'])) {
                    $laudoMae[] = [
                        'marcador' => $maeAl['marcador'],
                        'include' => 'V'
                    ];
                    \Log::info("Condição 3 cumprida" . $maeAl['marcador']);
                } else {
                    \Log::info("Nenhuma condição cumprida" . $maeAl['marcador']);
                }
            }
        } else {
            $laudoMae = null;
        }

        // Comparar alelos entre pai e animal
        if ($pai != null) {
            foreach ($animal->alelos as $animalAlelo) {
                foreach ($pai->alelos as $paiAlelo) {
                    if ($animalAlelo->marcador == $paiAlelo->marcador) {
                        $alelosPai[] = [
                            'marcador' => $animalAlelo->marcador,
                            'alelo1' => $animalAlelo->alelo1,
                            'alelo2' => $animalAlelo->alelo2,
                            'aleloPai1' => $paiAlelo->alelo1,
                            'aleloPai2' => $paiAlelo->alelo2,
                        ];
                        break;
                    }
                }
            }

            foreach ($alelosPai as $paiAl) {
                if (($paiAl['alelo1'] != '' || $paiAl['alelo2'] != '') && ($paiAl['aleloPai1'] != '' || $paiAl['aleloPai2'] != '')) {
                    if (
                        $paiAl['alelo1'] == $paiAl['aleloPai1'] ||
                        $paiAl['alelo1'] == $paiAl['aleloPai2'] ||
                        $paiAl['alelo2'] == $paiAl['aleloPai1'] ||
                        $paiAl['alelo2'] == $paiAl['aleloPai2']
                    ) {
                        $laudoPai[] = [
                            'marcador' => $paiAl['marcador'],
                            'include' => 'P'
                        ];
                        \Log::info("Condição 1 cumprida" . $paiAl['marcador']);
                    } else {
                        $laudoPai[] = [
                            'marcador' => $paiAl['marcador'],
                            'include' => ''
                        ];
                        \Log::info("Condição 2 cumprida" . $paiAl['marcador']);
                    }
                } elseif ($paiAl['alelo1'] == '' && $paiAl['alelo2'] == '' || empty($paiAl['aleloPai1']) && empty($paiAl['aleloPai2'])) {
                    $laudoPai[] = [
                        'marcador' => $paiAl['marcador'],
                        'include' => 'V'
                    ];
                    \Log::info("Condição 3 cumprida" . $paiAl['marcador']);
                } else {
                    \Log::info("Nenhuma condição cumprida" . $paiAl['marcador']);
                }
            }
        } else {
            $laudoPai = null;
        }

        \Log::info($laudoPai);

        return response()->json([
            'laudoMae' => $laudoMae,
            'laudoPai' => $laudoPai,
            'animal' => $animal,
            'pai' => $pai,
            'mae' => $mae,
        ]);
    }



    public function gerarBarCode($id)
    {
        $ordem = OrdemServico::find($id);
        $animal = Animal::with('alelos')->find($ordem->animal_id);
        $ordem->update([
            'bar_code' => $animal->register_number_brand ?? null,
        ]);
        $generator = new BarcodeGeneratorPNG();
        $barcode = $generator->getBarcode($ordem->codlab, $generator::TYPE_CODE_128);

        $barcodex = base64_encode($barcode);

        return view('admin.ordem-servico.bar-code', get_defined_vars());
    }
}
