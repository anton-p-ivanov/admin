<?php

namespace app\components\formatters;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use yii\web\ResponseFormatterInterface;

/**
 * Class CSVResponseFormatter
 *
 * @package app\components\formatters
 */
class CSVResponseFormatter implements ResponseFormatterInterface
{
    /**
     * @param \yii\web\Response $response
     */
    public function format($response)
    {
        $filename = 'export_' . date('Y-m-d_H-i-s') . '.csv';

        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0);

        $isHeaderRendered = false;
        $index = 0;

        foreach ($response->data as $model) {
            $sheet = $spreadsheet->getActiveSheet();

            if (!$isHeaderRendered) {
                $attributes = array_keys($model);
                foreach ($attributes as $column => $attribute) {
                    $sheet->setCellValueByColumnAndRow($column + 1, $index + 1, $attribute);

                }
                $isHeaderRendered = true;
            }

            $model = array_values($model);
            for ($j = 0; $j < count($model); $j++) {
                $sheet->setCellValueByColumnAndRow($j + 1, $index + 2, $model[$j]);
            }

            $index++;
        }

        $writer = new Csv($spreadsheet);

        // Force download file
        header('Content-type: text/csv');
        header("Content-Disposition: attachment; filename=\"{$filename}\"");
        $writer->save('php://output');

        \Yii::$app->end();
    }
}