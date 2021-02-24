<?php

namespace App\Imports;

use Log;
use App\Job;
use App\Career;
use App\Sector;
use App\Location;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class JobsImport implements ToCollection
{
    /**
     * @param Collection $rows
     * @return null|void
     */
    public function collection(Collection $rows)
    {
        $rowCount = 0;
        foreach($rows as $row) {

            $rowCount++;

            if($row[0] == null) {
                break;
            }

            if($rowCount < 3) {
                continue;
            }

            // Split into array if there are multiple sectors
            $sectors = explode(',', $row[0]);
            $sector_ids = [];

            // Sectors Table
            foreach ($sectors as $key => $sector) {
                $sector = Sector::firstOrNew(['name' => trim($sector)]);
                $sector->save();

                $sector_ids[] = $sector->id;
            }

            // Careers Table
            $career = Career::firstOrNew(['name' => trim($row[1])]);
            $career->save();

            // Update Pivot Table
            $career->sectors()->sync($sector_ids);

            // Update Locations Table
            $location_import_array = explode(',', $row[3]);
            $location = Location::firstOrNew(
                ['city' => trim($row[3])],
                ['province' => end($location_import_array), 'country' => 'South Africa']
            );
            $location->save();

            $job = new Job([
                'career_id'             => $career->id,
                'career'                => $career->id,
                'description'           => $row[2],
                'location'              => $row[3],
                'location_id'           => $location->id,
                'company'               => $row[4],
                'address'               => $row[5],
                'website'               => $row[6],
                'job_mentor'            => array('name' => $row[7], 'telephone' => $row[8], 'email' => $row[9]),
                'hr_contact'            => array('name' => $row[10], 'telephone' => $row[11], 'email' => $row[12]),
                'backup_job_mentor'     => array('name' => $row[13], 'telephone' => $row[14], 'email' => $row[15]),
                'availability'          => array('period_1' => $row[16], 'period_2' => $row[17], 'period_3' => $row[18], 'period_4' => $row[19], 'period_5' => $row[20]),
                'total_days'            => $row[21],
                'arrival_time'          => $row[22],
                'collection_time'       => $row[23],
                'max_applicants'        => ($row[24]) ?: 3,
                'company_code'          => $row[25],
                'days_per_job_shadow'   => $row[26] ? $row[26] : 1,
                'indemnity_file'        => $row[27] ? json_encode(explode(',', $row[27])) : null,
                'amount'                => 350.00
            ]);

            $job->save();

            // Update Pivot Table
            $job->sectors()->sync($sector_ids);
        }

        return;
    }
}
