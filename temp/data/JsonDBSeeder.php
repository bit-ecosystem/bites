<?php

namespace Database\Seeders;

use Bites\Base\Services\ModelTableMapper;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class JsonDBSeeder extends Seeder
{
    public function run()
    {
        // Get all JSON files in the database/data directory
        dump(__DIR__.'/../data');
        // $files = File::files(database_path('data'));
        $files = File::files(__DIR__.'/../data');

        // Get the array of tables with their associated models
        $mapper = new ModelTableMapper;
        $tablesWithModels = $mapper->getTablesWithModels();
        foreach ($files as $file) {
            if ($file->getExtension() === 'json') {
                // Read the JSON file
                $json = File::get($file->getPathname());
                $data = json_decode($json, true);
                dump($file->getFilename());
                // Iterate through each table in the JSON data
                foreach ($data as $table => $records) {
                    if (array_key_exists($table, $tablesWithModels)) {
                        foreach ($records as $record) {
                            dump($record);
                            // Insert or update the record into the corresponding table using models
                            $this->insertOrUpdateRecord($tablesWithModels, $table, $record);
                        }
                    }
                }
            }
        }
    }

    private function insertOrUpdateRecord($tablesWithModels, $table, $record, $parentId = null, $parentKey = null)
    {
        $modelClass = $tablesWithModels[$table];

        // Prepare the main record data
        $mainRecord = $record;
        foreach ($record as $key => $value) {
            if (is_array($value)) {
                unset($mainRecord[$key]);
            }
        }

        // Add parent ID if available
        if ($parentId && $parentKey) {
            $mainRecord[$parentKey.'_id'] = $parentId;
        }

        // Ensure foreign keys are set to null if they are empty
        foreach ($mainRecord as $key => $value) {
            if (empty($value)) {
                $mainRecord[$key] = null;
            }
        }

        // Insert or update the main record and get its instance
        $mainModel = $modelClass::firstOrCreate($mainRecord);
        // Check if the record contains nested data for related tables
        foreach ($record as $key => $value) {
            if (is_array($value) && array_key_exists($key, $tablesWithModels)) {
                // Insert the related records
                foreach ($value as $relatedRecord) {
                    $this->insertOrUpdateRecord($tablesWithModels, $key, $relatedRecord, $mainModel->id, $table);
                }
            }
        }
    }
}
