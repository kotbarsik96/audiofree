<?php

return [
  'accepted' => 'Поле :attribute должно быть принято.',
  'accepted_if' => 'Поле :attribute должно быть принято, когда :other равно :value.',
  'active_url' => 'Поле :attribute должно быть действительным URL.',
  'after' => 'Поле :attribute должно быть датой после :date.',
  'after_or_equal' => 'Поле :attribute должно быть датой после или равной :date.',
  'alpha' => 'Поле :attribute может содержать только буквы.',
  'alpha_dash' => 'Поле :attribute может содержать только буквы, цифры, дефисы и нижние подчеркивания.',
  'alpha_num' => 'Поле :attribute может содержать только буквы и цифры.',
  'array' => 'Поле :attribute должно быть массивом.',
  'ascii' => 'Поле :attribute должно содержать только однобайтовые алфавитно-цифровые символы и символы.',
  'before' => 'Поле :attribute должно быть датой до :date.',
  'before_or_equal' => 'Поле :attribute должно быть датой до или равной :date.',
  'between' => [
    'array' => 'Поле :attribute должно содержать от :min до :max элементов.',
    'file' => 'Поле :attribute должно быть от :min до :max килобайт.',
    'numeric' => 'Поле :attribute должно быть от :min до :max.',
    'string' => 'Поле :attribute должно содержать от :min до :max символов.',
  ],
  'boolean' => 'Поле :attribute должно быть истинным или ложным.',
  'can' => 'Поле :attribute содержит неавторизованное значение.',
  'confirmed' => 'Подтверждение для поля :attribute не совпадает.',
  'current_password' => 'Неверный пароль.',
  'date' => 'Поле :attribute должно быть корректной датой.',
  'date_equals' => 'Поле :attribute должно быть датой, равной :date.',
  'date_format' => 'Поле :attribute не соответствует формату :format.',
  'decimal' => 'Поле :attribute должно содержать :decimal десятичных знаков.',
  'declined' => 'Поле :attribute должно быть отклонено.',
  'declined_if' => 'Поле :attribute должно быть отклонено, когда :other равно :value.',
  'different' => 'Поля :attribute и :other должны различаться.',
  'digits' => 'Поле :attribute должно содержать :digits цифр.',
  'digits_between' => 'Поле :attribute должно содержать от :min до :max цифр.',
  'dimensions' => 'Поле :attribute имеет недопустимые размеры изображения.',
  'distinct' => 'Поле :attribute содержит повторяющееся значение.',
  'discount' => 'Скидка - процент до :max',
  'doesnt_end_with' => 'Поле :attribute не должно заканчиваться одним из следующих значений: :values.',
  'doesnt_start_with' => 'Поле :attribute не должно начинаться с одного из следующих значений: :values.',
  'email' => 'Поле :attribute должно быть действительным электронным адресом.',
  'ends_with' => 'Поле :attribute должно заканчиваться одним из следующих значений: :values.',
  'enum' => 'Выбранное значение для :attribute недопустимо.',
  'exists' => 'Выбранное значение для :attribute недопустимо.',
  'extensions' => 'Поле :attribute должно иметь одно из следующих расширений: :values.',
  'file' => 'Поле :attribute должно быть файлом.',
  'filled' => 'Поле :attribute должно иметь значение.',
  'gt' => [
    'array' => 'Поле :attribute должно содержать больше :value элементов.',
    'file' => 'Поле :attribute должно быть больше :value килобайт.',
    'numeric' => 'Поле :attribute должно быть больше :value.',
    'string' => 'Поле :attribute должно быть больше :value символов.',
  ],
  'gte' => [
    'array' => 'Поле :attribute должно содержать :value элементов или больше.',
    'file' => 'Поле :attribute должно быть больше или равно :value килобайт.',
    'numeric' => 'Поле :attribute должно быть больше или равно :value.',
    'string' => 'Поле :attribute должно быть больше или равно :value символов.',
  ],
  'hex_color' => 'Поле :attribute должно быть допустимым шестнадцатеричным цветом.',
  'image' => 'Допустимые форматы изображений: :mimes; максимальный вес: :max килобайт',
  'image_path' => 'Изображение должно быть загружено на сервер',
  'in' => 'Выбранное значение для :attribute недопустимо.',
  'in_array' => 'Поле :attribute должно существовать в :other.',
  'integer' => 'Поле :attribute должно быть целым числом.',
  'ip' => 'Поле :attribute должно быть допустимым IP-адресом.',
  'ipv4' => 'Поле :attribute должно быть допустимым IPv4-адресом.',
  'ipv6' => 'Поле :attribute должно быть допустимым IPv6-адресом.',
  'json' => 'Поле :attribute должно быть допустимой строкой JSON.',
  'lowercase' => 'Поле :attribute должно быть в нижнем регистре.',
  'lt' => [
    'array' => 'Поле :attribute должно содержать менее :value элементов.',
    'file' => 'Поле :attribute должно быть меньше :value килобайт.',
    'numeric' => 'Поле :attribute должно быть меньше :value.',
    'string' => 'Поле :attribute должно быть меньше :value символов.',
  ],
  'lte' => [
    'array' => 'Поле :attribute не должно содержать более :value элементов.',
    'file' => 'Поле :attribute должно быть меньше или равно :value килобайт.',
    'numeric' => 'Поле :attribute должно быть меньше или равно :value.',
    'string' => 'Поле :attribute должно быть меньше или равно :value символов.',
  ],
  'mac_address' => 'Поле :attribute должно быть допустимым MAC-адресом.',
  'max' => [
    'array' => 'Поле :attribute не должно содержать более :max элементов.',
    'file' => 'Поле :attribute не должно быть больше :max килобайт.',
    'numeric' => 'Поле :attribute не должно быть больше :max.',
    'string' => 'Не более :max символов',
  ],
  'max_digits' => 'Поле :attribute не должно содержать более :max цифр.',
  'mimes' => 'Поле :attribute должно быть файлом одного из следующих типов: :values.',
  'mimetypes' => 'Поле :attribute должно быть файлом одного из следующих типов: :values.',
  'min' => [
    'array' => 'Не менее :min элементов',
    'file' => 'Не менее :min килобайт',
    'numeric' => 'Не менее :min',
    'string' => 'Не менее :min символов',
  ],
  'min_digits' => 'Не менее :min цифр.',
  'missing' => 'Поле :attribute должно отсутствовать.',
  'missing_if' => 'Поле :attribute должно отсутствовать, когда :other равно :value.',
  'missing_unless' => 'Поле :attribute должно отсутствовать, если :other не равно :value.',
  'missing_with' => 'Поле :attribute должно отсутствовать, когда присутствует :values.',
  'missing_with_all' => 'Поле :attribute должно отсутствовать, когда присутствуют :values.',
  'multiple_of' => 'Поле :attribute должно быть кратным :value.',
  'not_in' => 'Выбранное значение для :attribute недопустимо.',
  'not_regex' => 'Формат поля :attribute недопустим.',
  'numeric' => 'Поле :attribute должно быть числом.',
  'password' => [
    'letters' => 'Поле :attribute должно содержать хотя бы одну букву.',
    'mixed' => 'Поле :attribute должно содержать хотя бы одну заглавную и одну строчную букву.',
    'numbers' => 'Поле :attribute должно содержать хотя бы одну цифру.',
    'symbols' => 'Поле :attribute должно содержать хотя бы один символ.',
    'uncompromised' => 'Указанное значение :attribute появилось в утечке данных. Пожалуйста, выберите другое значение :attribute.',
  ],
  'present' => 'Поле :attribute должно присутствовать.',
  'present_if' => 'Поле :attribute должно присутствовать, когда :other равно :value.',
  'present_unless' => 'Поле :attribute должно присутствовать, если :other не равно :value.',
  'present_with' => 'Поле :attribute должно присутствовать, когда присутствует :values.',
  'present_with_all' => 'Поле :attribute должно присутствовать, когда присутствуют :values.',
  'prohibited' => 'Поле :attribute запрещено.',
  'prohibited_if' => 'Поле :attribute запрещено, когда :other равно :value.',
  'prohibited_unless' => 'Поле :attribute запрещено, если :other не находится в :values.',
  'prohibits' => 'Поле :attribute запрещает присутствие :other.',
  'regex' => 'Формат поля :attribute недопустим.',
  'required' => 'Поле :attribute обязательно для заполнения.',
  'required_array_keys' => 'Поле :attribute должно содержать записи для: :values.',
  'required_if' => 'Поле :attribute обязательно для заполнения, когда :other равно :value.',
  'required_if_accepted' => 'Поле :attribute обязательно для заполнения, когда :other принято.',
  'required_unless' => 'Поле :attribute обязательно для заполнения, если :other не находится в :values.',
  'required_with' => 'Поле :attribute обязательно для заполнения, когда присутствует :values.',
  'required_with_all' => 'Поле :attribute обязательно для заполнения, когда присутствуют :values.',
  'required_without' => 'Поле :attribute обязательно для заполнения, когда :values отсутствует.',
  'required_without_all' => 'Поле :attribute обязательно для заполнения, когда отсутствуют все :values.',
  'same' => 'Поля :attribute и :other должны совпадать.',
  'size' => [
    'array' => 'Поле :attribute должно содержать :size элементов.',
    'file' => 'Поле :attribute должно быть :size килобайт.',
    'numeric' => 'Поле :attribute должно быть :size.',
    'string' => 'Поле :attribute должно содержать :size символов.',
  ],
  'starts_with' => 'Поле :attribute должно начинаться с одного из следующих значений: :values.',
  'string' => 'Поле :attribute должно быть строкой.',
  'timezone' => 'Поле :attribute должно быть допустимым часовым поясом.',
  'unique' => 'Значение поля :attribute уже занято.',
  'uploaded' => 'Загрузка поля :attribute не удалась.',
  'uppercase' => 'Поле :attribute должно быть в верхнем регистре.',
  'url' => 'Поле :attribute должно быть допустимым URL.',
  'ulid' => 'Поле :attribute должно быть допустимым ULID.',
  'uuid' => 'Поле :attribute должно быть допустимым UUID.',

  'password.min' => 'Пароль должен содержать не менее :min символов',
  'password.mixed' => 'Необходимы заглавные и строчные буквы в пароле',
  'password.numbers' => 'Необходимы цифры в пароле',
  'password.required' => 'Не указан пароль',
  'email.required' => 'Не указан email',
  'email.email' => 'Неверный формат email',
  'email.unique' => 'Пользователь уже существует',
  'username.required' => 'Не указано имя',
  'name.min' => 'Имя от 2 символов',
  'phone_number.regex' => 'Неверный формат телефона',

  'name.required' => 'Не указано название',
  'name.min' => 'От :min символов',
  'price.numeric' => 'Неверный формат цены',
  'price.min' => 'Минимальная цена должна быть :min',
  'quantity' => 'Неверное количество',
  'status.exists' => 'Статус не существует',
  'type.exists' => 'Тип не существует',
  'category.exists' => 'Категория не существует',
  'brand.exists' => 'Бренд не существует',
  'required' => 'Не указано поле',
  'rating_value' => 'Значение рейтинга должно быть до :max',
  'productName.unique' => 'Название товара занято',
  'address.min' => 'Недействительный адрес',
  'address.required' => 'Необходимо указать адрес',
  'emailAlreadyVerified' => 'Почта уже подтверждена',
  'incorrectCode' => 'Недействительный код',
  'incorrectLink' => 'Недействительная ссылка',

  'product' => [
    'attachmentDoesntExist' => 'Вложение не существует',
    'description' => [
      'max' => 'Описание должно содержать не более :max символов'
    ],
  ],

  'taxonomy' => [
    'slug' => 'Метка (URL) обязательно должна присутствовать в виде уникальной строки',
    'name' => 'Название обязательно должно присутствовать в виде уникальной строки',
    'group' => 'Группа должна быть строкой'
  ],

  'taxonomyValue' => [
    'slug' => 'Метка (URL) обязательно должна ссылаться на таксономию',
    'value' => 'Значение обязательно должно присутствовать и быть уникальным среди этой таксономии',
    'value_slug' => 'Метка значения обязательно должна присутствовать и быть уникальной среди этой таксономии',
  ],

  'cart' => [
    'variation_id' => 'Вариация не существует'
  ],

  'incorrectLoginOrPassword' => 'Неверный логин или пароль',

  /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

  'custom' => [
    'attribute-name' => [
      'rule-name' => 'custom-message',
    ],
  ],

  /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

  'attributes' => [],

];
