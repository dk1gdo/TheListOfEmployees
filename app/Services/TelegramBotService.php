<?php

namespace App\Services;

use App\Classes\Telegram;
use App\Exports\EmployeesExport;
use App\Models\Employee;
use App\Repositories\EmployeeRepository;
use http\Message;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class TelegramBotService
{
    protected $tg;
    protected $chat_id;
    protected $message;
    protected $default_keyboard;
    protected $message_id;
    protected $callback_action;

     protected array $inline_keyboard_dismiss = [
            'inline_keyboard' => [
                [
                    [
                        'text' => 'Уволить',
                        'callback_data' => '/dismiss',
                    ],
                ],
            ]
    ];
    public function __construct($request)
    {

        $this->tg = new Telegram();
        if(isset($request->message)){
            $this->chat_id = $request->message->chat->id;
            $this->message = $request->message->text;
        }
        if(isset($request->callback_query)){
            $this->callback_action = $request->callback_query->data;
            $this->chat_id = $request->callback_query->message->chat->id;
            $this->message_id = $request->callback_query->message->message_id;
        }
        $this->default_keyboard = $this->makeKayboard("keyboard",
            [
                [
                    ['text' => "Добавить сотрудника"],
                    ['text' => "Действующие сотрудники"],
                ],
                [
                    ['text' => "Уволенные сотрудники"],
                    ['text' => "Excel-файл"],
                ],
            ],
        );

    }

    public function action() {
        if (!is_null($this->callback_action)){
            $callback = explode(" ", $this->callback_action);
            switch ($callback[0]){
                case '/dismiss':
                    $this->dismiss($this->chat_id, $this->message_id, $callback[1]);
                    break;
                case '/restore':
                    $this->restore($this->chat_id, $this->message_id, $callback[1]);
                    break;
            }
            return;
        }
        switch ($this->message){
            case "/start":
                $this->tg->sendMessage($this->chat_id, "Бот для работы с базой сотрудников", $this->default_keyboard);
                break;
            case "add":
            case "/add":
            case "Добавить сотрудника":
                $this->add($this->chat_id, $this->message);
                break;
            case "current":
            case "/current":
            case "Действующие сотрудники":
                $this->current($this->chat_id);
                break;
            case "fired":
            case "/fired":
            case "Уволенные сотрудники":
                $this->fired($this->chat_id);
                break;
            case "excel":
            case "/excel":
            case "Excel-файл":
                $this->excel($this->chat_id);
                break;
            default:
                if (Storage::exists($this->chat_id)) {
                    $this->add($this->chat_id, $this->message);
                    break;
                }
                $this->tg->sendMessage($this->chat_id, "Не знаю такой команды {$this->callback_action}", $this->default_keyboard);
        }
    }

    public function add($chat_id, $message){
        $data = [];
        if (!Storage::exists($chat_id)){
            $data["step"] = 1;
            Storage::put($chat_id, json_encode($data));
            $this->tg->sendMessage($chat_id, "Введите ФИО сотрудника для внесения в базу", $this->default_keyboard);
        } else {
            $data = json_decode(Storage::get($chat_id), true);
            switch ($data["step"]) {
                case 1:
                    $data["step"] = 2;
                    $data["name"] = $message;
                    Storage::put($chat_id, json_encode($data));
                    $this->tg->sendMessage($chat_id, "Введите должность для " . $data['name'], $this->default_keyboard);
                    break;
                case 2:
                    $data["step"] = 3;
                    $data["job"] = $message;
                    Storage::put($chat_id, json_encode($data));
                    $this->tg->sendMessage($chat_id, "Введите номер телефона в формате +79999999999 для " . $data['name'] . " с должностью " . $data["job"], $this->default_keyboard);
                    break;
                case 3:
                    if (preg_match('~^(?:\+7)\d{10}$~', $message)){
                        $data["step"] = 4;
                        $data["phone"] = $message;
                        Storage::put($chat_id, json_encode($data));
                        $this->tg->sendMessage($chat_id, "Введите дату рождения в формате дд.мм.гггг для " . $data['name'] . " с должностью " . $data["job"] . " и номером телефона " . $data["phone"], $this->default_keyboard);
                    } else {
                        $this->tg->sendMessage($chat_id, "Неверно введен номер телефона, повторите попытку", $this->default_keyboard);
                    }
                    break;
                case 4:
                    $tmp = explode('.', $message);
                    if(count($tmp) != 3) {
                        $this->tg->sendMessage($chat_id, "Несуществующая дата! Повтрорите ввод даты", $this->default_keyboard);
                        break;
                    }
                    if(checkdate($tmp[1], $tmp[0], $tmp[2])){
                        $data["step"] = 5;
                        $data["birthday"] = $tmp[2] . "-" . $tmp[1] . "-" . $tmp[0];
                        if (time() < strtotime('+18 years', strtotime($data["birthday"]))) {
                            $this->tg->sendMessage($chat_id, "Работнику должно быть больше 18 лет!", $this->default_keyboard);
                            break;
                        }
                        Storage::put($chat_id, json_encode($data));
                        $repo = new EmployeeRepository();
                        $repo->createEmployee([
                            "name"              => $data['name'],
                            "job"               => $data['job'],
                            "phone"             => $data['phone'],
                            "birthday"          => $data['birthday'],
                            "employment_date"   => date('Y-m-d'),
                        ]);
                        Storage::delete($chat_id);
                        $this->tg->sendMessage($chat_id, "Данные внесены!", $this->default_keyboard);
                    } else {
                        $this->tg->sendMessage($chat_id, "Несуществующая дата! Повтрорите ввод даты", $this->default_keyboard);
                    }
                    break;
            }
        }
    }

    public function current($chat_id){
        $repo = new EmployeeRepository();
        $employees = $repo->getCurrentEmployees();
        foreach ($employees as $employee){
            $this->tg->sendMessage($chat_id, "
                {$employee->name}, должность: {$employee->job->title}, телефон: {$employee->phone}, дата рождения: " .
                date_format(date_create($employee->birthday), "d.m.Y") .
                ", дата приема " . date_format(date_create($employee->employment_date), "d.m.Y"), $this->makeKayboard("inline_keyboard", [[['text' => "Уволить", "callback_data" => "/dismiss {$employee->id}"]]]));
        }
    }

    public function makeKayboard($type, array $buttons){
        return [
            $type => $buttons,
            'one_time_keyboard' => TRUE,
            'resize_keyboard' => TRUE,
        ];
    }

    public function dismiss($chat_id, $message_id, $employee_id){
        $repo = new EmployeeRepository();
        $employee = $repo->getEmployee($employee_id);
        $employee->dismissal_date = date('Y-m-d');
        $employee->push();
        $this->tg->deleteMessage($chat_id, $message_id);
    }

    public function fired($chat_id){
        $repo = new EmployeeRepository();
        $employees = $repo->getFiredEmployees();
        foreach ($employees as $employee){
            $this->tg->sendMessage($chat_id, "
                {$employee->name}, должность: {$employee->job->title}, телефон: {$employee->phone}, дата рождения: " .
                date_format(date_create($employee->birthday), "d.m.Y") .
                ", дата приема " . date_format(date_create($employee->employment_date), "d.m.Y") .
                ", дата увольнения " . date_format(date_create($employee->dismissal_date), "d.m.Y"),
                $this->makeKayboard("inline_keyboard", [[['text' => "Вернуть", "callback_data" => "/restore {$employee->id}"]]]));
        }
    }

    public function restore($chat_id, $message_id, $employee_id){
        $repo = new EmployeeRepository();
        $employee = $repo->getEmployee($employee_id);
        $employee->employment_date = date('Y-m-d');
        $employee->dismissal_date = null;
        $employee->push();
        $this->tg->deleteMessage($chat_id, $message_id);
    }

    public function excel($chat_id){
        Excel::store(new EmployeesExport, 'EmployeesExport.xlsx');
        //dd();
        $this->tg->sendDocument($chat_id);
    }

}
