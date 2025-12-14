<?php

namespace App\Imports;

use App\Models\TrackList;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToModel;

class TracksImport implements ToModel, SkipsOnError
{

    use Importable;
    private $date;

    public function __construct(string $date)
    {
        $this->date = $date;
    }
    /**
     * @param \Throwable $e
     */
    public function onError(\Throwable $e)
    {
        // Handle the exception how you'd like.
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Удаляем квадратные скобки, табы и пробелы
        $trackCode = preg_replace('/[\[\]\s\t]/', '', $row[0]);

        return new TrackList([
            'track_code' => $trackCode,
            'to_china' => $this->date,
            'status' => 'Получено в Китае',
            'reg_china' => 1,
            'created_at' => date(now()),
        ]);
    }
}
