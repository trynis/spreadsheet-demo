<?php

namespace App\Service;

interface GoogleSheetApiInterface
{
    /**
     * @return void
     */
    public function init();

    /**
     * @param string $spreadSheetId
     * @param array $array
     */
    public function insertHeaders(string $spreadSheetId, array $array);

    /**
     * @param string $spreadSheetId
     * @param array $array
     * @param int $rowNumber
     */
    public function insertRow(string $spreadSheetId, array $array, int $rowNumber);

    /**
     * @param string $spreadSheetId
     * @param array $rows
     * @param int $startingRowNumber
     */
    public function insertRows(string $spreadSheetId, array $rows, int $startingRowNumber);
}