<?php

namespace App\Imports;

use App\Models\TrackList;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class TracksImport implements ToModel, SkipsOnError, WithCustomCsvSettings
{

    use Importable;
    private $date;
    private string $delimiter;

    public function __construct(string $date, string $delimiter = ',')
    {
        $this->date = $date;
        $this->delimiter = $delimiter;
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
        // Предохранитель: если строка пустая
        if (!isset($row[0])) {
            return null;
        }

        // Удаляем квадратные скобки, табы и пробелы
        $trackCode = preg_replace('/[\[\]\s\t]/', '', $row[0]);

        if ($trackCode === '') {
            return null;
        }

        return new TrackList([
            'track_code' => $trackCode,
            'to_china' => $this->date,
            'status' => 'Получено в Китае',
            'reg_china' => 1,
            'created_at' => date(now()),
        ]);
    }

    public function getCsvSettings(): array
    {
        return [
            // Разделитель задаётся извне (автоопределение в контроллере), кодировка UTF-8
            'delimiter' => $this->delimiter,
            'enclosure' => '"',
            'escape' => '\\',
            'input_encoding' => 'UTF-8',
        ];
    }
}
