<?php
namespace Src;

class FormHandler
{
    private AmoSender $sender;

    public function __construct(AmoSender $sender)
    {
        $this->sender = $sender;
    }

    public function process(array $formData): array
    {
        $name = $formData['name'] ?? '';
        $email = $formData['email'] ?? '';
        $phone = $formData['phone'] ?? '';
        $price = $formData['price'] ?? '';
        $moreThan30 = $formData['more_than_30'] ?? '0';

        if (!$name || !$email || !$phone || !$price) {
            Logger::write("Ошибка: не заполнены все поля.");
            throw new \Exception('Пожалуйста, заполните все поля.');
        }

        $payload = [
            'name' => 'Заявка с сайта',
            'price' => (int)$price,
            '_embedded' => [
                'contacts' => [[
                    'name' => $name,
                    'custom_fields_values' => [
                        ['field_code' => 'EMAIL', 'values' => [['value' => $email]]],
                        ['field_code' => 'PHONE', 'values' => [['value' => $phone]]]
                    ]
                ]]
            ],
            'custom_fields_values' => [
                ['field_id' => 1669485, 'values' => [['value' => strval($moreThan30)]]]
            ]
        ];

        return $this->sender->send($payload);
    }
}
