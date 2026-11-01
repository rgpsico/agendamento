<?php

namespace Tests\Feature;

use App\Mail\LeadTemplateMail;
use ReflectionClass;
use Tests\TestCase;

class LeadTemplateMailTest extends TestCase
{
    /** @test */
    public function reenvia_com_espera_crescente_quando_o_resend_limita(): void
    {
        $padrao = (new ReflectionClass(LeadTemplateMail::class))->getDefaultProperties();

        $this->assertGreaterThan(1, $padrao['tries']);
        $this->assertSame($padrao['backoff'], collect($padrao['backoff'])->sort()->values()->all(), 'a espera deve crescer');
        $this->assertGreaterThanOrEqual(2, $padrao['backoff'][0]);
    }
}
