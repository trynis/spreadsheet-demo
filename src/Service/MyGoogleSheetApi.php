<?php

namespace App\Service;

class MyGoogleSheetApi implements GoogleSheetApiInterface
{
    /**
     * @var array
     */
    protected $authFile;

    /**
     * @var \Google_Service_Sheets
     */
    private $service;

    public function __construct(string $authFile)
    {
        $this->authFile = $authFile;
    }

    private function getClient()
    {
        $client = new \Google_Client();

        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . realpath($this->authFile));
        $client->useApplicationDefaultCredentials();

        $client->setApplicationName('Performance Media Google Sheets Demo');
        $client->setScopes(['https://www.googleapis.com/auth/spreadsheets']);

        return $client;
    }

    public function init()
    {
        $client = $this->getClient();
        $this->service = new \Google_Service_Sheets($client);
    }

    public function insertHeaders(string $spreadSheetId, array $array)
    {
        $this->insertRow($spreadSheetId, $array);
    }

    public function insertRow(string $spreadSheetId, array $row, int $rowNumber = 1)
    {
        $body = new \Google_Service_Sheets_ValueRange(['values' => [array_values($row)]]);
        $params = ['valueInputOption' => 'RAW'];
        $this->service->spreadsheets_values->update($spreadSheetId, 'A' . $rowNumber, $body, $params);
    }

    public function insertRows(string $spreadSheetId, array $rows, int $startingRowNumber = 1)
    {
        $data = [];
        foreach ($rows as $row) {
            $data[] = new \Google_Service_Sheets_ValueRange([
                'range' => 'A' . $startingRowNumber++,
                'values' => [array_values($row)],
            ]);
        }

        $body = new \Google_Service_Sheets_BatchUpdateValuesRequest([
            'valueInputOption' => 'RAW',
            'data' => $data,
        ]);

        $this->service->spreadsheets_values->batchUpdate($spreadSheetId, $body);
    }
}