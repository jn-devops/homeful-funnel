<?php

namespace App\Filament\Pages;

use App\Models\Contact;
use Filament\Pages\Page;

class SocialMediaProspect extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.social-media-prospect';
    protected static ?string $navigationLabel='Social Media Prospects';
    public $activeTab ='prospects';

}
