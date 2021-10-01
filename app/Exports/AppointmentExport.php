<?php

namespace App\Exports;

use App\Models\Client;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class AppointmentExport implements FromQuery, WithHeadings, WithMapping, WithColumnFormatting
{
    use Exportable;

    private $appointment = 0;

    public function __construct($appointment)
    {
        $this->appointment = $appointment;
    }

    public function query()
    {
        return Client::query()->with('appointments.time')->whereHas('appointments', function ($appointments) {
            $appointments->where('appointment_id', $this->appointment);
        });
    }

    public function headings(): array
    {
        return [
            'SN',
            'SAMPLE ID',
            'RACK NO',
            'EMIRATES ID',
            'MRN',
            'PATIENT NAME',
            'BIRTHDATE',
            'GENDER',
            'COLLECTION DATE AND TIME',
            'PASSPORT NUMBER',
            'NATIONALITY',
            'HASANA ID',
            'AI(ADDITIONAL IDENTIFIER)',
            'MOBILE NUMBER',
            'EMAIL',
            'CITY',
            'ADDRESS',
        ];
    }

    public function map($client): array
    {
        $time = $client->appointment($this->appointment)->date . " " . $client->appointment($this->appointment)->time->time;
        $datetime = Carbon::parse($time)->toDateTime();
        return [
            $client->id,
            $client->sample_id,
            $client->rack_number,
            $client->id_type == "Emirates ID" ? $client->id_number : "",
            $client->mrn,
            $client->name,
            $client->dob,
            $client->gender,
            \PhpOffice\PhpSpreadsheet\Shared\Date::dateTimeToExcel($datetime),
            $client->id_type == "Passport" ? $client->id_number : "",
            $client->nationality,
            $client->alhasna_number,
            $client->ai,
            $client->contact_number,
            $client->email,
            $client->city,
            $client->address,

        ];
    }

    public function columnFormats(): array
    {
        return [
            'I' => NumberFormat::FORMAT_DATE_DATETIME,
        ];
    }

}
