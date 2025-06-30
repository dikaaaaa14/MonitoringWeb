app/Http/Controllers/ExportController.php
public function exportCSV()
{
    $data = SensorData::all();
    $csvExporter = new \Laracsv\Export();
    $csvExporter->build($data, ['created_at', 'temperature', 'humidity', 'ph', 'gas', 'location']);
    return $csvExporter->download('sensor_data.csv');
}