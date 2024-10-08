<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void{
        parent::setUp();

        DB::delete('delete from courses');
        DB::delete('delete from cpmk');
        DB::delete('delete from cpl');
        DB::delete('delete from teaching_classes');
        DB::delete('delete from users');
    }

}
