<?php

namespace Tests;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function createApplication()
    {
        $app = parent::createApplication();

        $traits = class_uses_recursive(static::class);
        $refreshesDatabase = isset($traits[LazilyRefreshDatabase::class])
            || isset($traits[RefreshDatabase::class]);

        if ($refreshesDatabase && $app['config']->get('database.default') === 'mysql') {
            throw new \RuntimeException('RefreshDatabase tests must not run against MySQL.');
        }

        return $app;
    }
}
