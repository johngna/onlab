<?php

namespace App\Console\Commands;

use App\Mail\SendAlert;
use Bluerhinos\phpMQTT;
use App\Models\SensorReading;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
        $mqtt = new phpMQTT("ec2-54-207-218-159.sa-east-1.compute.amazonaws.com", 1883, env('MQTT_NAME'));
        if ($mqtt->connect()) {

            $mqtt->debug = true;
            $this->info("Connected to MQTT broker");

            $topics = [];

            // Assina todos os tópicos
            $topics['#'] = array('qos' => 0, 'function' => function ($topic, $message) {

                $this->info("Received message on topic: $topic. Message: $message");
                $this->processa($topic, $message);
            });

            $mqtt->subscribe($topics, 0);


            // Processa as mensagens normalmente
            while ($mqtt->proc()) {
                // Processamento contínuo
            }

            $mqtt->close();
            $this->info("MQTT connection closed");
        } else {
            $this->error("Failed to connect to MQTT broker");
        }
    }

    public function processa($topic, $message)
    {
        // $this->info("Received message on topic: $topic. Message: $message");
        if (strpos($topic, 'sensores') !== false) {
            $this->procMsgSensores($topic, $message);
        } else if (strpos($topic, 'avisos') !== false) {
            $this->procMsgAvisos($topic, $message);
        } else if (strpos($topic, 'reservatorios') !== false) {
            $this->procMsgReserv($topic, $message);
        }else{

        }
    }

    public function procMsgSensores($topic, $msg)
    {
        $equipamento = explode('/', $topic)[0];
        $last = DB::table('sensor_readings')->where('equipment_code', $equipamento)->orderBy('created_at', 'desc')->first();
        $count = DB::table('sensor_readings')->where('equipment_code', $equipamento)->count();

        $data = json_decode($msg, true);


        if ($data) {
            if ($count > 0 && $this->isDuplicate($last, $data)) {

            }else{
                DB::table('sensor_readings')->insert([
                    'equipment_code' => $equipamento,
                    'cd_ou' => (int) $data['Pu_ou'],
                    'cd_md' => (int) $data['Pu_md'],
                    'cd_in' => (int) $data['Pu_in'],
                    'fx_md' => (float) $data['Fx_md'],
                    'fx_in' => (float) $data['Fx_in'],
                    'temp1' => (float) $data['Temp1'],
                    'tplc1' => (float) $data['Tplc1'],
                    't_pre' => (float) $data['T_pre'],
                    // 'gal_0' => (float) $data['Gal_0'],
                    // 'gal_1' => (float) $data['Gal_1'],
                    // 'gal_2' => (float) $data['Gal_2'],
                    // 'gal_3' => (float) $data['Gal_3'],
                    'created_at' => now(),
                ]);
            }


        } else {
            $this->error("Failed to decode JSON message");
        }
    }


    public function procMsgAvisos($topic, $msg)
    {
        $equipamento = explode('/', $topic)[0];
        $last = DB::table('alert_readings')->where('equipment_code', $equipamento)->orderBy('created_at', 'desc')->first();
        $count = DB::table('alert_readings')->where('equipment_code', $equipamento)->count();

        $data = json_decode($msg, true);
        //substituir S/D por null
        foreach ($data as $key => $value) {
            if($value == 'S/D'){
                $data[$key] = null;
            }
        }


        if ($data) {
            if ($count > 0 && $this->isDuplicateAvisos($last, $data)) {

            }else{
                DB::table('alert_readings')->insert([
                    'equipment_code' => $equipamento,
                    'status' => $data['Status'],
                    'flags' => $data['Flags'],
                    'alarmes' => $data['Alarmes'],
                    'created_at' => now(),

                ]);


                //enviar notificação
                // if($data['Alarmes'] != 'S/D'){
                //     //enviar email
                //     Mail::to(env('SUPPORT_EMAIL'))->send(new SendAlert($data['Alarmes']));

                // }


            }


        } else {
            $this->error("Failed to decode JSON message");
        }
    }

    public function procMsgReserv($topic, $msg)
    {
        $equipamento = explode('/', $topic)[0];
        $last = DB::table('reserv_readings')->where('equipment_code', $equipamento)->orderBy('created_at', 'desc')->first();
        $count = DB::table('reserv_readings')->where('equipment_code', $equipamento)->count();

        $data = json_decode($msg, true);

        if ($data) {
            if ($count > 0 && $this->isDuplicateReserv($last, $data)) {

            }else{
                DB::table('reserv_readings')->insert([
                    'equipment_code' => $equipamento,
                    'gal_0' => $data['Gal_0'],
                    'gal_1' => $data['Gal_1'],
                    'gal_2' => $data['Gal_2'],
                    'gal_3' => $data['Gal_3'],
                    'created_at' => now(),

                ]);

            }


        } else {
            $this->error("Failed to decode JSON message");
        }
    }

    private function isDuplicate($last, $data)
    {
        return $last->cd_ou == $data['Pu_ou'] &&
               $last->cd_md == $data['Pu_md'] &&
               $last->cd_in == $data['Pu_in'] &&
               $last->fx_md == $data['Fx_md'] &&
               $last->fx_in == $data['Fx_in'] &&
               $last->temp1 == $data['Temp1'] &&
               $last->tplc1 == $data['Tplc1'] &&
               $last->t_pre == $data['T_pre'];
    }

    private function isDuplicateAvisos($last, $data)
    {
        return $last->status == $data['Status'] &&
               $last->flags == $data['Flags'] &&
               $last->alarmes == $data['Alarmes'];
    }

    private function isDuplicateReserv($last, $data)
    {
        return $last->gal_0 == $data['Gal_0'] &&
               $last->gal_1 == $data['Gal_1'] &&
               $last->gal_2 == $data['Gal_2'] &&
               $last->gal_3 == $data['Gal_3'];
    }
}
