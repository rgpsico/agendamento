<?php

namespace App\View\Components;

use Illuminate\View\Component;

class HeroSection extends Component
{
    public $title;
    public $subtitle;
    public $image;
    public $primaryButton;
    public $secondaryButton;

    public function __construct($title, $subtitle, $image = null, $primaryButton = null, $secondaryButton = null)
    {
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->image = $image;
        $this->primaryButton = $primaryButton;
        $this->secondaryButton = $secondaryButton;
    }

    public function render()
    {
        return view('components.hero-section');
    }
}
