<?php

namespace App\View\Components\Site;

use Illuminate\View\Component;

class ContactSection extends Component
{
    public $site;

    public function __construct($site)
    {
        $this->site = $site;
    }

    public function render()
    {
        return view('components.site.contact-section');
    }
}
