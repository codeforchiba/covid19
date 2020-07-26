<?php
require __DIR__.'/vendor/autoload.php';
use Carbon\Carbon;
use Tightenco\Collect\Support\Collection;

function makeDateArray($begin) : Collection{
  $begin = Carbon::parse($begin);
  $dates = [];
  while(true) {

    if ($begin->diffInDays(Carbon::now()) == 1) {
      break;
    } else {
      $dates[$begin->addDay()->format('Y-m-d').'T08:00:00.000Z'] =0;

    }
  }
  return new Collection($dates);
}
function formatDate(string $date) :string
{
    if (preg_match('#(\d+/\d+/\d+)/ (\d+:\d+)#', $date, $matches)) {
      $carbon = Carbon::parse($matches[1].' '.$matches[2]);
      return $carbon->format('Y/m/d H:i');
    } else {
      throw new Exception('Can not parse date:'.$date);
    }
}

function xlsxToArray(string $path, string $sheet_name, string $range, $header_range = null)
{
  $reader = new PhpOffice\PhpSpreadsheet\Reader\Xlsx();
  $spreadsheet = $reader->load($path);
  $sheet = $spreadsheet->getSheetByName($sheet_name);
  $data =  new Collection($sheet->rangeToArray($range));
  $data = $data->map(function ($row) {
    return new Collection($row);
  });
  if ($header_range !== null) {
      $headers = xlsxToArray($path, $sheet_name, $header_range)[0];
      // TODO check same columns length
      return $data->map(function ($row) use($headers){
          return $row->mapWithKeys(function ($cell, $idx) use($headers){

            return [
              $headers[$idx] => $cell
            ];
        });
      });
  }

  return $data;
}


function readContacts() : array
{

  $data = xlsxToArray(__DIR__.'/downloads/04callcenter.xlsx', 'Sheet1', 'A2:E365', 'A1:E1');
  return [
    'date' => xlsxToArray(__DIR__.'/downloads/04callcenter.xlsx', 'Sheet1', 'H1')[0][0],
    'data' => $data->filter(function ($row) {
        return $row['曜日'] && $row['17-21時'];
      })->map(function ($row) {
      $date = '2020-'.str_replace(['月', '日'], ['-', ''], $row['日付']);
      $carbon = Carbon::parse($date);
      $row['日付'] = $carbon->format('Y-m-d').'T08:00:00.000Z';
      $row['date'] = $carbon->format('Y-m-d');
      $row['w'] = $carbon->format('w');
      $row['short_date'] = $carbon->format('m/d');
      $row['小計'] = array_sum([
        $row['9-13時'] ?? 0,
        $row['13-17時'] ?? 0,
        $row['17-21時'] ?? 0,
      ]);
      return $row;
    })
  ];
}

/*
 * 取り急ぎreadContactsからコピペ
 * 過渡期がすぎたら共通処理にしたい。→マクロ入ってる
 */
function readQuerents() : array
{
  $data = xlsxToArray(__DIR__.'/downloads/05returnee.xlsx', 'RAW', 'A2:D365', 'A1:D1');

  return [
    'date' => xlsxToArray(__DIR__.'/downloads/05returnee.xlsx', 'RAW', 'H1')[0][0],
    'data' => $data->filter(function ($row) {

      return $row['曜日'];
    })->map(function ($row) {
      $date = '2020-'.str_replace(['月', '日'], ['-', ''], $row['日付']);
      $carbon = Carbon::parse($date);
      $row['日付'] = $carbon->format('Y-m-d').'T08:00:00.000Z';
      $row['date'] = $carbon->format('Y-m-d');
      $row['w'] = $carbon->format('w');
      $row['short_date'] = $carbon->format('m/d');
      $row['小計'] = array_sum([
        $row['9-17時'] ?? 0,
        $row['17-翌9時'] ?? 0,
      ]);
      return $row;
    })->values()
  ];
}

function readPatients() : array
{
    $data = xlsxToArray(__DIR__.'/downloads/01patient.xlsx', 'RAW', 'A2:E365', 'A1:E1');

    return [
      'date' => xlsxToArray(__DIR__.'/downloads/01patient.xlsx', 'RAW', 'M1')[0][0],
      'data' => $data->filter(function ($row) {
        return $row['リリース日'];
      })->map(function ($row) {
        $date = '2020-'.str_replace(['月', '日'], ['-', ''], $row['リリース日']);
        $carbon = Carbon::parse($date);
        $row['リリース日'] = $carbon->format('Y-m-d').'T08:00:00.000Z';
        $row['date'] = $carbon->format('Y-m-d');
        $row['w'] = $carbon->format('w');
        $row['short_date'] = $carbon->format('m/d');
        return $row;
      })
    ];
}

function createSummary(array $patients) {
  $dates = makeDateArray('2020-01-23');

  return [
    'date' => $patients['date'],
    'data' => $dates->map(function ($val, $key) {
      return [
        '日付' => $key,
        '小計' => $val
      ];
    })->merge($patients['data']->groupBy('リリース日')->map(function ($group, $key) {
      return [
        '日付' => $key,
        '小計' => $group->count()
      ];
    }))->values()
  ];
}

function readInspections() : array{
  $data = xlsxToArray(__DIR__.'/downloads/03inspection.xlsx', '入力シート', 'A2:J365', 'A1:J1');
  $data = $data->filter(function ($row) {
    return $row['疑い例検査'] !== null;
  });
  return [
    'date' => '2020/04/17/ 12:55', //TODO 現在のエクセルに更新日付がないので変更する必要あり
    'data' => $data-> map(function ($row) {
      $date = str_replace(['月', '日'], ['/', ''], $row['判明日']);
      $carbon = Carbon::parse($date);
      $row['判明日'] = str_replace(['2020-'],[''],$carbon->format('m/d/yy'));      
      return $row;
    })    
  ];
}

function readInspectionsSummary(array $inspections) : array
{
  return [
    'date' => $inspections['date'],
    'data' => [
      '市内' => $inspections['data']->map(function ($row) {
        return str_replace(' ', '', $row['（小計①）']);
      }),
      'その他' => $inspections['data']->map(function ($row) {
        return str_replace(' ', '', $row['（小計②）']);
      }),
    ],
    'labels' =>$inspections['data']->map(function ($row) {
        return Carbon::parse($row['判明日'])->format('n/j');
    })
  ];
}

//実行時のパラメータとして更新日付を指定できるように変更
$DefaultUpdate='';
if($argc == 2) {
  $DefaultUpdate = formatDate($argv[1]);
} else {
  print_r('php convert.php [DefaultUpdate(YYYY/MM/DD/ HH:MM)]');
}

$contacts = readContacts();
$querents = readQuerents();
$patients = readPatients();
$patients_summary = createSummary($patients);
$inspections =readInspections();
$inspections_summary =readInspectionsSummary($inspections);

$data = compact([
  'contacts',
  'querents',
  'patients',
  'patients_summary',
  'inspections',
  'inspections_summary'
]);
$lastUpdate = '';
$lastTime = 0;
foreach ($data as $key => &$arr) {
  if($DefaultUpdate) {
    $arr['date'] = $DefaultUpdate;
  } else {
    $arr['date'] = formatDate($arr['date']);
  }
  $timestamp = Carbon::parse()->format('YmdHis');
  if ($lastTime <= $timestamp) {
    $lastTime = $timestamp;
    $lastUpdate = Carbon::parse($arr['date'])->format('Y/m/d H:i');
  }
}
$data['lastUpdate'] = $lastUpdate;

$data['main_summary'] = [
  'attr' => '検査実施人数',
  'value' => xlsxToArray(__DIR__.'/downloads/02summary.xlsx', '検査実施サマリ', 'A2')[0][0],
  'children' => [
    [
      'attr' => '陽性患者数',
      'value' => xlsxToArray(__DIR__.'/downloads/02summary.xlsx', '検査実施サマリ', 'A5')[0][0],
      'children' => [
        [
          'attr' => '入院中',
          'value' => xlsxToArray(__DIR__.'/downloads/02summary.xlsx', '検査実施サマリ', 'A8')[0][0],
          'children' => [
            [
              'attr' => '軽症・中等症',
              'value' => xlsxToArray(__DIR__.'/downloads/02summary.xlsx', '検査実施サマリ', 'A11')[0][0]
            ],
            [
              'attr' => '重症',
              'value' => xlsxToArray(__DIR__.'/downloads/02summary.xlsx', '検査実施サマリ', 'A14')[0][0]
            ]
          ]
        ],
        [
          'attr' => '退院',
          'value' => xlsxToArray(__DIR__.'/downloads/02summary.xlsx', '検査実施サマリ', 'A20')[0][0]
        ],
        [
          'attr' => '死亡',
          'value' => xlsxToArray(__DIR__.'/downloads/02summary.xlsx', '検査実施サマリ', 'A17')[0][0]
        ]

      ]
    ]
  ]
];

file_put_contents(__DIR__.'/../data/data.json', json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
