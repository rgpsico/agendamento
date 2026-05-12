<?php

use App\Models\Bot;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Bot::whereNull('widget_token')->each(function (Bot $bot) {
            $bot->updateQuietly(['widget_token' => Str::random(40)]);
        });
    }

    public function down(): void
    {
        // irreversível — não remove tokens já gerados
    }
};
