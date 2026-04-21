<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;

class CustomEditProfile extends BaseEditProfile
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Giữ lại các ô mặc định (Tên, Email, Mật khẩu)
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                
                // 🌟 THÊM Ô SỐ ĐIỆN THOẠI CỦA BẠN VÀO ĐÂY
                TextInput::make('phone')
                    ->label('Số điện thoại')
                    ->tel()
                    ->maxLength(15),
                    
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }
}