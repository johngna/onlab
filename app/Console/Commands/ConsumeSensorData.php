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
         //$this->info("Received message on topic: $topic. Message: $message");
        if (strpos($topic, 'sensores') !== false) {
            $this->procMsgSensores($topic, $message);
        } else if (strpos($topic, 'avisos') !== false) {
            $this->procMsgAvisos($topic, $message);
        } else if (strpos($topic, 'reservatorios') !== false) {
            $this->procMsgReserv($topic, $message);
        } else if (strpos($topic, 'dados') !== false) {
            $this->procMsgDados($topic, $message);
        } else if (strpos($topic, 'sensor_nivel') !== false) {
            $this->procMsgLevel($topic, $message);
        } else if (strpos($topic, 'tempo') !== false) {
            $this->procMsgTime($topic, $message);
        } else if (strpos($topic, 'atuadores/gerais') !== false) {
            $this->procMsgActuator($topic, $message);
        } else if (strpos($topic, 'atuadores/bombas') !== false) {
            $this->procMsgBomb($topic, $message);
        } else if (strpos($topic, 'atuadores/valvulas') !== false) {
            $this->procMsgValve($topic, $message);
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
                    'cd_ou' => (float) $data['Pu_ou'],
                    'cd_md' => (float) $data['Pu_md'],
                    'cd_in' => (float) $data['Pu_in'],
                    'fx_md' => (float) $data['Fx_md'],
                    'fx_in' => (float) $data['Fx_in'],
                    'temp1' => (float) $data['Temp1'],
                    'tplc1' => (float) $data['Tplc1'],
                    't_pre' => (float) $data['Tr_pres'],
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

    private function procMsgDados($topic, $msg)
    {

        $equipamento = explode('/', $topic)[0];

        $data = json_decode($msg, true);

        DB::table('data_readings')->insert([
            'equipment_code' => $equipamento,
            'modelo' => $data['Modelo'],
            'data' => \Carbon\Carbon::createFromFormat('d/m/Y H:i', $data['Data'] . ' ' . $data['Hora']),
            'created_at' => now(),
        ]);

    }

    private function procMsgLevel($topic, $msg)
    {


        $equipamento = explode('/', $topic)[0];

        $last = DB::table('level_readings')->where('equipment_code', $equipamento)->orderBy('created_at', 'desc')->first();
        $count = DB::table('level_readings')->where('equipment_code', $equipamento)->count();
        $data = json_decode($msg, true);

        if($count > 0){


            if(!$this->isDuplicateLevel($last, $data)){

                DB::table('level_readings')->insert([
                    'equipment_code' => $equipamento,
                    's1' => $data['s1'],
                    's2' => $data['s2'],
                    's3' => $data['s3'],
                    'created_at' => now(),
                ]);
            }
        }else{

            DB::table('level_readings')->insert([
                'equipment_code' => $equipamento,
                's1' => $data['S1'],
                's2' => $data['S2'],
                's3' => $data['S3'],
                'created_at' => now(),
            ]);
        }



    }

    private function procMsgTime($topic, $msg)
    {


        $equipamento = explode('/', $topic)[0];

        $last = DB::table('time_readings')->where('equipment_code', $equipamento)->orderBy('created_at', 'desc')->first();
        $count = DB::table('time_readings')->where('equipment_code', $equipamento)->count();
        $data = json_decode($msg, true);

        if($count > 0){


            if(!$this->isDuplicateTime($last, $data)){

                DB::table('time_readings')->insert([
                    'equipment_code' => $equipamento,
                    't_prime' => $data['T_Prime'],
                    't_loop' => $data['T_Loop'],
                    't_prod' => $data['T_Prod'],
                    't_dados' => $data['T_Dados'],
                    'created_at' => now(),
                ]);
            }
        }else{

            DB::table('time_readings')->insert([
               'equipment_code' => $equipamento,
                    't_prime' => $data['T_Prime'],
                    't_loop' => $data['T_Loop'],
                    't_prod' => $data['T_Prod'],
                    't_dados' => $data['T_Dados'],
                    'created_at' => now(),
            ]);
        }



    }

    private function procMsgActuator($topic, $msg)
    {


        $equipamento = explode('/', $topic)[0];

        $last = DB::table('actuator_readings')->where('equipment_code', $equipamento)->orderBy('created_at', 'desc')->first();
        $count = DB::table('actuator_readings')->where('equipment_code', $equipamento)->count();
        $data = json_decode($msg, true);

        if($count > 0){


            if(!$this->isDuplicateActuator($last, $data)){

                DB::table('actuator_readings')->insert([
                    'equipment_code' => $equipamento,
                    'som' => $data['Som'],
                    'lamp_uv' => $data['Lamp_Uv'],
                    'b_pwm' => $data['B_PWM'],
                    'coolers' => $data['Coolers'],
                    'created_at' => now(),
                ]);
            }
        }else{

            DB::table('actuator_readings')->insert([
               'equipment_code' => $equipamento,
                    'som' => $data['Som'],
                    'lamp_uv' => $data['Lamp_Uv'],
                    'b_pwm' => $data['B_PWM'],
                    'coolers' => $data['Coolers'],
                    'created_at' => now(),
            ]);
        }



    }

    private function procMsgBomb($topic, $msg)
    {


        $equipamento = explode('/', $topic)[0];

        $last = DB::table('bomb_readings')->where('equipment_code', $equipamento)->orderBy('created_at', 'desc')->first();
        $count = DB::table('bomb_readings')->where('equipment_code', $equipamento)->count();
        $data = json_decode($msg, true);

        if($count > 0){


            if(!$this->isDuplicateBomb($last, $data)){

                DB::table('bomb_readings')->insert([
                    'equipment_code' => $equipamento,
                    'b_pres' => $data['B_Pres'],
                    'b_rec' => $data['B_Rec'],
                    'created_at' => now(),
                ]);
            }
        }else{

            DB::table('bomb_readings')->insert([
               'equipment_code' => $equipamento,
               'b_pres' => $data['B_Pres'],
               'b_rec' => $data['B_Rec'],
               'created_at' => now(),
            ]);
        }



    }

    private function procMsgValve($topic, $msg)
    {


        $equipamento = explode('/', $topic)[0];

        $last = DB::table('valve_readings')->where('equipment_code', $equipamento)->orderBy('created_at', 'desc')->first();
        $count = DB::table('valve_readings')->where('equipment_code', $equipamento)->count();
        $data = json_decode($msg, true);

        if($count > 0){


            if(!$this->isDuplicateValve($last, $data)){

                DB::table('valve_readings')->insert([
                    'equipment_code' => $equipamento,
                    'vd' => $data['VD'],
                    'vl' => $data['VL'],
                    've' => $data['VE'],
                    'vr' => $data['VR'],
                    'v5' => $data['V5'],
                    'v6' => $data['V6'],
                    'v7' => $data['V7'],
                    'v8' => $data['V8'],
                    'created_at' => now(),
                ]);
            }
        }else{

            DB::table('valve_readings')->insert([
               'equipment_code' => $equipamento,
               'vd' => $data['VD'],
               'vl' => $data['VL'],
               've' => $data['VE'],
               'vr' => $data['VR'],
               'v5' => $data['V5'],
               'v6' => $data['V6'],
               'v7' => $data['V7'],
               'v8' => $data['V8'],
               'created_at' => now(),
            ]);
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
               $last->t_pre == $data['Tr_pres'];
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

    private function isDuplicateLevel($last, $data)
    {
        return $last->s1 == $data['S1'] &&
               $last->s2 == $data['S2'] &&
               $last->s3 == $data['S3'];
    }

    private function isDuplicateTime($last, $data)
    {
        return $last->t_prime == $data['T_Prime'] &&
               $last->t_loop == $data['T_Loop'] &&
               $last->t_prod == $data['T_Prod'] &&
               $last->t_dados == $data['T_Dados'];
    }

    private function isDuplicateActuator($last, $data)
    {
        return $last->som == $data['Som'] &&
               $last->lamp_uv == $data['Lamp_Uv'] &&
               $last->b_pwm == $data['B_PWM'] &&
               $last->coolers == $data['Coolers'];
    }

    private function isDuplicateBomb($last, $data)
    {
        return $last->b_pres == $data['B_Pres'] &&
               $last->b_rec == $data['B_Rec'];
    }

    private function isDuplicateValve($last, $data)
    {
        return $last->vd == $data['VD'] &&
               $last->vl == $data['VL'] &&
               $last->ve == $data['VE'] &&
               $last->vr == $data['VR'] &&
               $last->v5 == $data['V5'] &&
               $last->v6 == $data['V6'] &&
               $last->v7 == $data['V7'] &&
               $last->v8 == $data['V8'];
    }


}
