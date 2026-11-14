<?php

namespace App\Filament\Portal\Pages\Auth;

use App\Models\AuthorizedUserId;
use App\Models\User;
use Filament\Auth\Pages\Register as BaseRegister;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class Register extends BaseRegister {
    private function getPrograms(): array {
        return [
            'BEED' => 'BEED - Bachelor of Elementary Education',
            'BPA' => 'BPA - Bachelor of Public Administration',
            'BPA-FA' => 'BPA-FA - Bachelor of Public Administration major in Fiscal Administration',
            'BSA' => 'BSA - Bachelor of Science in Accountancy',
            'BSAM' => 'BSAM - Bachelor of Science in Agribusiness Management',
            'BSArch' => 'BSArch - Bachelor of Science in Architecture',
            'BSBA-FM' => 'BSBA-FM - BSBA major in Financial Management',
            'BSBA-MM' => 'BSBA-MM - BSBA major in Marketing Management',
            'BSBio' => 'BSBio - Bachelor of Science in Biology',
            'BSCE' => 'BSCE - Bachelor of Science in Civil Engineering',
            'BSED-MT' => 'BSED-MT - BSED major in Mathematics',
            'BSEE' => 'BSEE - Bachelor of Science in Electrical Engineering',
            'BSHM' => 'BSHM - Bachelor of Science in Hospitality Management',
            'BSIT' => 'BSIT - Bachelor of Science in Information Technology',
            'BSND' => 'BSND - Bachelor of Science in Nutrition and Dietetics',
            'BSOA' => 'BSOA - Bachelor of Science in Office Administration',
            'DCVET' => 'DCVET - Diploma in Civil Engineering Technology',
            'DCET' => 'DCET - Diploma in Computer Engineering Technology',
            'DEET' => 'DEET - Diploma in Electrical Engineering Technology',
            'DIT' => 'DIT - Diploma in Information Technology',
        ];
    }

    public function form(Schema $schema): Schema {
        return $schema
            ->components([
                Select::make('user_type')
                    ->label('Account Type')
                    ->options([
                        'Student' => 'Student',
                        'Faculty' => 'Faculty',
                    ])
                    ->required()
                    ->live(),

                TextInput::make('user_id')
                    ->label('Student / Faculty ID')
                    ->placeholder('20XX-XXXXX-LQ-X or 5-digit Faculty ID')
                    ->required()
                    ->maxLength(20)
                    ->unique(User::class, 'user_id')
                    ->rules([
                        function ($get) {
                            return function (string $attribute, $value, $fail) use ($get) {
                                $userType = $get('user_type');

                                if ($userType === 'Student' && ! preg_match('/^20\d{2}-\d{5}-LQ-\d$/', $value)) {
                                    $fail('Invalid Student ID. Use the format 20XX-XXXXX-LQ-X.');
                                    return;
                                }

                                if ($userType === 'Faculty' && ! preg_match('/^\d{5}$/', $value)) {
                                    $fail('Invalid Faculty ID. It must contain exactly 5 digits.');
                                    return;
                                }

                                if (! $userType) {
                                    return;
                                }

                                $authorizedId = AuthorizedUserId::where('user_id', $value)->first();

                                if (! $authorizedId) {
                                    $fail('This ID is not authorized to register. Please contact the administrator.');
                                    return;
                                }

                                if ($authorizedId->user_type !== $userType) {
                                    $fail('This ID does not match the selected account type.');
                                    return;
                                }

                                if (! $authorizedId->is_active) {
                                    $fail('This ID is currently inactive. Please contact the administrator.');
                                    return;
                                }

                                if ($authorizedId->claimed_by !== null) {
                                    $fail('This ID has already been used to register an account.');
                                }
                            };
                        },
                    ]),

                TextInput::make('name')
                    ->label('Full Name')
                    ->placeholder('Enter your complete name')
                    ->required()
                    ->maxLength(255)
                    ->regex("/^[\p{L}\s.'-]+$/u")
                    ->validationMessages([
                        'regex' => 'Your name can only contain letters, spaces, periods, apostrophes, and hyphens.',
                    ]),

                Select::make('program')
                    ->label('Program')
                    ->options($this->getPrograms())
                    ->searchable()
                    ->preload()
                    ->visible(fn ($get) => $get('user_type') === 'Student')
                    ->required(fn ($get) => $get('user_type') === 'Student'),

                Select::make('year_level')
                    ->label('Year Level')
                    ->options([
                        '1st Year' => '1st Year',
                        '2nd Year' => '2nd Year',
                        '3rd Year' => '3rd Year',
                        '4th Year' => '4th Year',
                        '5th Year' => '5th Year',
                    ])
                    ->visible(fn ($get) => $get('user_type') === 'Student')
                    ->required(fn ($get) => $get('user_type') === 'Student'),

                Select::make('section')
                    ->label('Section')
                    ->options([
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                    ])
                    ->visible(fn ($get) => $get('user_type') === 'Student')
                    ->required(fn ($get) => $get('user_type') === 'Student'),

                TextInput::make('email')
                    ->label('Email Address (Optional)')
                    ->placeholder('example@gmail.com')
                    ->nullable()
                    ->rules([
                        'nullable',
                        'string',
                        'email:rfc',
                        'max:255',
                        'unique:users,email',
                    ])
                    ->validationMessages([
                        'email' => 'Please enter a valid email address.',
                        'unique' => 'This email address is already registered.',
                        'max' => 'Email address cannot exceed 255 characters.',
                    ]),

                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->revealable()
                    ->required()
                    ->rule(Password::min(8))
                    ->same('passwordConfirmation'),

                TextInput::make('passwordConfirmation')
                    ->label('Confirm Password')
                    ->password()
                    ->revealable()
                    ->required()
                    ->dehydrated(false),
            ])
            ->statePath('data');
    }

    protected function handleRegistration(array $data): User {
        $validated = Validator::make($data, [
            'user_type' => [
                'required',
                Rule::in(['Student', 'Faculty']),
            ],
            'user_id' => [
                'required',
                'string',
                'max:20',
                'unique:users,user_id',
                function ($attribute, $value, $fail) use ($data) {
                    if (($data['user_type'] ?? null) === 'Student' && ! preg_match('/^20\d{2}-\d{5}-LQ-\d$/', $value)) {
                        $fail('Invalid Student ID format.');
                    }

                    if (($data['user_type'] ?? null) === 'Faculty' && ! preg_match('/^\d{5}$/', $value)) {
                        $fail('Faculty ID must contain exactly 5 digits.');
                    }
                },
            ],
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{L}\s.\'-]+$/u',
            ],
            'program' => [
                Rule::requiredIf(($data['user_type'] ?? null) === 'Student'),
                'nullable',
                Rule::in(array_keys($this->getPrograms())),
            ],
            'year_level' => [
                Rule::requiredIf(($data['user_type'] ?? null) === 'Student'),
                'nullable',
                Rule::in([
                    '1st Year',
                    '2nd Year',
                    '3rd Year',
                    '4th Year',
                    '5th Year',
                ]),
            ],
            'section' => [
                Rule::requiredIf(($data['user_type'] ?? null) === 'Student'),
                'nullable',
                Rule::in(['1', '2', '3']),
            ],
            'email' => [
                'nullable',
                'email:rfc',
                'max:255',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'string',
                Password::min(8),
            ],
        ], [
            'user_id.unique' => 'This ID is already registered.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'name.regex' => 'Your name contains invalid characters.',
        ]);

        if ($validated->fails()) {
            throw ValidationException::withMessages(
                $validated->errors()->toArray()
            );
        }

        $validatedData = $validated->validated();

        return DB::transaction(function () use ($validatedData, $data) {
            $authorizedId = AuthorizedUserId::where('user_id', $validatedData['user_id'])
                ->lockForUpdate()
                ->first();

            if (! $authorizedId) {
                throw ValidationException::withMessages([
                    'user_id' => 'This ID is not authorized to register. Please contact the administrator.',
                ]);
            }

            if ($authorizedId->user_type !== $validatedData['user_type']) {
                throw ValidationException::withMessages([
                    'user_id' => 'This ID does not match the selected account type.',
                ]);
            }

            if (! $authorizedId->is_active) {
                throw ValidationException::withMessages([
                    'user_id' => 'This ID is currently inactive. Please contact the administrator.',
                ]);
            }

            if ($authorizedId->claimed_by !== null) {
                throw ValidationException::withMessages([
                    'user_id' => 'This ID has already been used to register an account.',
                ]);
            }

            $user = User::create([
                'user_id' => $validatedData['user_id'],
                'name' => $validatedData['name'],
                'user_type' => $validatedData['user_type'],
                'program' => $validatedData['program'] ?? null,
                'year_level' => $validatedData['year_level'] ?? null,
                'section' => $validatedData['section'] ?? null,
                'email' => $validatedData['email'] ?: null,
                'password' => $data['password'],
            ]);

            $authorizedId->update([
                'claimed_by' => $user->id,
                'claimed_at' => now(),
            ]);

            return $user;
        });
    }
}