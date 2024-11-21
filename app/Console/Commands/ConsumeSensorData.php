<?php

namespace App\Console\Commands;

use Bluerhinos\phpMQTT;
use App\Models\SensorReading;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ConsumeSensorData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mqtt:consume';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consume sensor data from MQTT';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $mqtt = new phpMQTT("ec2-54-207-218-159.sa-east-1.compute.amazonaws.com", 1883, "LaravelMQTTClient");
        if ($mqtt->connect()) {

            $mqtt->debug = true;
            $this->info("Connected to MQTT broker");

            $equipamentos = DB::table('equipamentos')->get();

            $topics = [];
            foreach ($equipamentos as $equipamento) {
                $topic = $equipamento->numero_serie . "/sensor";
                $topics[$topic] = array('qos' => 0, 'function' => array($this, 'procMsg'));
            }

            $mqtt->subscribe($topics, 0);
            $this->info("Subscribed to topics");

            while ($mqtt->proc()) {
                // This loop will process incoming messages
            }

            $mqtt->close();
            $this->info("MQTT connection closed");
        } else {
            $this->error("Failed to connect to MQTT broker");
        }
    }

    public function procMsg($topic, $msg)
    {
        $equipamento = explode('/', $topic)[0];
        $last = DB::table('sensor_readings')->where('equipment_code', $equipamento)->orderBy('created_at', 'desc')->first();
        $count = DB::table('sensor_readings')->where('equipment_code', $equipamento)->count();

        $data = json_decode($msg, true);
        if ($data) {
            if ($count > 0 && $this->isDuplicate($last, $data)) {
                return;
            }

            DB::table('sensor_readings')->insert([
                'equipment_code' => $equipamento,
                'cd_ou' => (int) $data['Cd_ou'],
                'cd_md' => (int) $data['Cd_md'],
                'cd_in' => (int) $data['Cd_in'],
                'fx_md' => (float) $data['Fx_md'],
                'fx_in' => (float) $data['Fx_in'],
                'temp1' => (float) $data['Temp1'],
                'tplc1' => (float) $data['Tplc1'],
                't_pre' => (float) $data['T_pre'],
                'gal_0' => (float) $data['Gal_0'],
                'gal_1' => (float) $data['Gal_1'],
                'gal_2' => (float) $data['Gal_2'],
                'gal_3' => (float) $data['Gal_3'],
                // 'created_at' => now(),
            ]);
        } else {
            $this->error("Failed to decode JSON message");
        }
    }

    private function isDuplicate($last, $data)
    {
        return $last->cd_ou == $data['Cd_ou'] &&
               $last->cd_md == $data['Cd_md'] &&
               $last->cd_in == $data['Cd_in'] &&
               $last->fx_md == $data['Fx_md'] &&
               $last->fx_in == $data['Fx_in'] &&
               $last->temp1 == $data['Temp1'] &&
               $last->tplc1 == $data['Tplc1'] &&
               $last->t_pre == $data['T_pre'] &&
               $last->gal_0 == $data['Gal_0'] &&
               $last->gal_1 == $data['Gal_1'] &&
               $last->gal_2 == $data['Gal_2'] &&
               $last->gal_3 == $data['Gal_3'];
    }
}
